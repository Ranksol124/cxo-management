<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MagazineResource\Pages;
use App\Filament\Resources\MagazineResource\RelationManagers;
use App\Models\Magazine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use App\Filament\Traits\HasResourcePermissions;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;

class MagazineResource extends Resource
{

    protected static ?string $model = Magazine::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Magazine')
                    ->extraAttributes([
                        'class' => 'filament-custom-section',
                    ])->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(255),

                            \Filament\Forms\Components\RichEditor::make('description')
                                ->label('Description')
                                ->toolbarButtons([
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'code',
                                    'codeBlock',
                                    'h1',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',

                                ])
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('file')
                                ->label('Magazine File')
                                ->disk('public')
                                ->directory('magazines')
                                ->visibility('public')
                                ->required()
                                ->preserveFilenames()
                                ->maxSize(15360)
                                ->acceptedFileTypes([
                                    'application/pdf', // PDF
                                    'application/msword', // DOC
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
                                    'image/jpeg', // JPG
                                    'image/png',  // PNG
                                    'image/gif',  // GIF
                                    'image/webp', // WEBP
                                ])
                                ->helperText('Allowed file types: PDF, DOC, DOCX, JPG, PNG, GIF, WEBP (max 15 MB)'),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // if NOT super-admin, only show active events
                if (!auth()->user()?->hasRole(['super-admin', 'admin'])) {
                    $query->where('status', 1);
                }
            })
            ->recordUrl(false)
            ->contentGrid([
                'md' => 3,
                'lg' => 4,
            ])
            ->columns([

                Stack::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('file')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                if (!$state) {
                                    return '<img src="' . asset('icons/no_icon.svg') . '">';
                                }

                                $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                                // Mapping extensions to icon paths
                                $icons = [
                                    'pdf' => asset('icons/pdf_icon.svg'),
                                    'doc' => asset('icons/word.svg'),
                                    'docx' => asset('icons/word.svg'),
                                    'xls' => asset('icons/excel.svg'),
                                    'xlsx' => asset('icons/excel.svg'),
                                    'txt' => asset('icons/text_icon.svg'),
                                    'zip' => asset('icons/zip_icon.svg'),
                                    'svg' => asset('icons/image.svg'),
                                    'jpg' => asset('icons/image.svg'),
                                    'jpeg' => asset('icons/image.svg'),
                                    'png' => asset('icons/image.svg'),
                                    'webp' => asset('icons/image.svg'),
                                ];

                                // Agar image hai to actual preview karo
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                    return '<img src="' . asset('storage/' . $state) . '" 
                            class=" h-40 !w-40 object-cover rounded-t-md" 
                            alt="' . e($ext) . ' file" />';
                                }

                                // Otherwise file icon show karo
                                $icon = $icons[$ext] ?? asset('icons/file.png');

                                return '<div class="flex items-center justify-center">
            <a href="' . asset('storage/' . $state) . '" download class="block">
                <img src="' . $icon . '" 
                     class="!w-40 h-40 object-cover rounded-t-md" 
                     alt="' . e($ext) . ' file" />
            </a>
        </div>';
                            }),
                    ])
                        ->extraAttributes(['class' => 'mb-4 text-center']), // 👈 fallback image

                    Stack::make([
                        TextColumn::make('title')->prefix('Title: ')->limit(50)->extraAttributes(['class' => 'font-semibold']),

                        BadgeColumn::make('file_type')->extraAttributes(['class' => 'mb-1']),
                        SelectColumn::make('status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(0) // default to Inactive
                            ->selectablePlaceholder(false) // prevents empty option
                            ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))->extraAttributes(['class' => 'my-1'])
                        // ToggleColumn::make('status')
                        //     ->label('Approved')
                        //     ->getStateUsing(fn($record) => $record->status == 1)
                        //     ->afterStateUpdated(function ($state, $record) {
                        //         $record->update([
                        //             'status' => $state ? 1 : 0,
                        //         ]);
                        //     })
                        //     ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))
                        //     ->onColor('success')
                        //     ->offColor('danger')->extraAttributes(['class' => 'mt-2'])
                    ]),

                ])->extraAttributes([
                            'class' => 'p-1 shadow-sm bg-white',
                        ]),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label('')
                    ->iconButton(),

                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->iconButton(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMagazines::route('/'),
            'create' => Pages\CreateMagazine::route('/create'),
            'edit' => Pages\EditMagazine::route('/{record}/edit'),
            'view' => Pages\ViewMagazine::route('/{record}'),
        ];
    }
}
