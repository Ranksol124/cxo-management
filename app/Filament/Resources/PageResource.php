<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),

                Builder::make('content')
                    ->blocks([
                        Block::make('hero_section')
                            ->label('Hero Section')
                            ->schema([
                                TextInput::make('title')->label('Main Heading')->required(),
                                TextInput::make('subtitle')->label('Sub title')->required(),
                                Textarea::make('description')->label('description'),

                                FileUpload::make('background_image')->image()->label('Background Image'),
                                TextInput::make('button_text')->label('Button Text'),
                                TextInput::make('button_link')->label('Button Link')->url(),
                                Toggle::make('enabled')->default(true),
                            ])->columns(2),

                        Block::make('features')
                            ->label('Features Grid')
                            ->schema([
                                Repeater::make('items')
                                    ->schema([
                                        FileUpload::make('image')->image()->label('Feature Image'),
                                        TextInput::make('tagline')->label('Tagline (small text)'),
                                        TextInput::make('heading')->label('Heading')->required(),
                                        Textarea::make('description')->label('Description'),
                                    ])
                                    ->minItems(4)
                                    ->maxItems(4) // is design ke liye 4 fixed
                                    ->columns(2),
                                Toggle::make('enabled')->default(true),
                            ]),

                    ])->columnSpanFull()

            ])

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable(),
                TextColumn::make('slug')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
