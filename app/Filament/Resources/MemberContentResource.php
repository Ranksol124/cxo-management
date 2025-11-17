<?php

namespace App\Filament\Resources;

use App\Enums\NewsType;
use App\Filament\Resources\MemberContentResource\Pages;
use App\Filament\Resources\MemberContentResource\RelationManagers;
use App\Models\MemberContent;
use Filament\Actions;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MemberContentResource extends Resource
{
    protected static ?string $model = MemberContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function canCreate(): bool
    {
        $roles = Auth::user()->roles()->pluck('name');
        return $roles->intersect(['member', 'enterprise-member', 'silver-member', 'gold-member'])->isNotEmpty() ? true : false;
    }
    public static function getNavigationLabel(): string
    {
        $roles = Auth::user()->roles()->pluck('name')->map(fn($r) => strtolower($r));

        if ($roles->intersect(['super-admin', 'admin'])->isNotEmpty()) {
            return 'Member Contents';
        }

        return 'My Content';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Member Content')->columns(3)
                    ->extraAttributes([
                        'class' => 'filament-custom-section',
                    ])->schema([

                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(255),

                            Select::make('content_type')
                                ->label('Content Type')
                                ->options([
                                    'News' => 'News',
                                    'Magazine' => 'Magazine',
                                ])
                                ->required()
                                ->reactive() // 👈 this is needed to trigger dependent field updates
                                ->afterStateUpdated(fn(callable $set) => $set('news_type', null)),

                            Select::make('news_type')
                                ->label('News Type')
                                ->options(NewsType::options())
                                ->visible(fn(callable $get) => $get('content_type') === 'News')
                                ->required(fn(callable $get) => $get('content_type') === 'News')
                                ->reactive(), // optional but good practice if more fields depend on this

                            Textarea::make('description')
                                ->label('Description')
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('content_attachments_files')
                                ->label('Upload attachments')
                                ->multiple()
                                ->disk('public')
                                ->directory('member_contents')->columnSpanFull()
                                ->maxSize(15360) // 15 MB in KB
                                ->helperText('Allowed file types: PDF, DOC, DOCX, JPG, PNG, GIF, WEBP (max 15 MB)'),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->hasRole(['member', 'enterprise-member', 'silver-member', 'gold-member'])) {
                    $query->where('member_id', Auth::user()->member->id);
                } else {
                    return $query;
                }
            })
            ->columns([
                TextColumn::make('member.full_name')->label('Member')->limit(50),
                Tables\Columns\TextColumn::make('title')->limit(50),
                Tables\Columns\TextColumn::make('content_type'),
                Tables\Columns\TextColumn::make('news_type'),
                SelectColumn::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->label('Status')
                    ->visible(function () {
                        $roles = Auth::user()->roles()->pluck('name')->map(fn($r) => strtolower($r));
                        return $roles->intersect(['super-admin', 'admin'])->isNotEmpty();
                    }),


                TextColumn::make('status_display')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->status)
                    ->color(fn(string $state) => match ($state) {
                        'Draft' => 'gray',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'secondary',
                    })
                    ->visible(function () {
                        $roles = Auth::user()->roles()->pluck('name')->map(fn($r) => strtolower($r));
                        return $roles->intersect(['super-admin', 'admin'])->isEmpty();
                    }),
            ])
            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(function () {
                        $roles = Auth::user()->roles()->pluck('name');
                        return $roles->intersect(['member', 'enterprise-member', 'silver-member', 'gold-member'])->isNotEmpty();
                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMemberContents::route('/'),
            'create' => Pages\CreateMemberContent::route('/create'),
            'edit' => Pages\EditMemberContent::route('/{record}/edit'),
            'view' => Pages\ViewMemberContent::route('/{record}'),
        ];
    }
}
