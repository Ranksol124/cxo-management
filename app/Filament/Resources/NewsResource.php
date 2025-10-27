<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use App\Enums\NewsType;
use App\Filament\Traits\HasResourcePermissions;
use Filament\Tables\Columns\Layout\Grid as LayoutGrid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    // protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make(2) // 2 columns
                    ->schema([
                        Section::make('News')
                            ->extraAttributes([
                                'class' => 'filament-custom-section',
                            ])->schema([
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required(),
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

                                    Select::make('news_type')
                                        ->label('News Type')
                                        ->options(NewsType::options()),
                                ])->columnSpan(1),

                        Section::make('Image')
                            ->extraAttributes([
                                'class' => 'filament-custom-section',
                            ])->schema([
                                    FileUpload::make('image')
                                        ->label('Image')
                                        ->disk('public')
                                        ->directory('news_images')
                                        ->image()
                                        ->previewable(),
                                    Select::make('website_preview')
                                        ->options([
                                            1 => 'Yes',
                                            0 => 'No',
                                        ])
                                        ->default(1),
                                    TextInput::make('link')->label('Link')
                                ])->columnSpan(1)
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {

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
                Tables\Columns\Layout\Stack::make([
                    Stack::make([
                        ImageColumn::make('image')
                            ->extraImgAttributes(['class' => 'rounded-t-md !h-40 !w-40'])
                            ->defaultImageUrl(asset('icons/no_icon.svg')),
                    ])->extraAttributes(['class' => 'mb-4 text-center']),

                    Stack::make([
                        Tables\Columns\TextColumn::make('title')->prefix('Title: ')->limit(50)->extraAttributes(['class' => 'font-semibold']),
                        Tables\Columns\TextColumn::make('news_type')->prefix('News Type: ')->extraAttributes(['class' => 'mb-1 mt-1']),
                        Tables\Columns\TextColumn::make('link')->extraAttributes(['class' => 'mb-1']),
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
            ->filters([])
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
            'view' => Pages\ViewNews::route('/{record}'),
        ];
    }
}
