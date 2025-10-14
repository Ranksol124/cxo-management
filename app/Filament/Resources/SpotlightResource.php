<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpotlightResource\Pages;
use App\Filament\Resources\SpotlightResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
class SpotlightResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Spotlight';

    protected static ?string $pluralLabel = 'Spotlights';

    protected static ?string $navigationLabel = 'Spotlight';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


            ]);
    }
    public static function canCreate(): bool
    {
        return false;
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')  ->extraAttributes(['class' => 'ml-[-340px]'])->label('Member Name')->sortable()->searchable(),
                TextColumn::make('email') ->extraAttributes(['class' => 'ml-[-160px]'])->label('Email')->sortable()->searchable(),
                Tables\Columns\ToggleColumn::make('spotlight')
                    ->label('Spotlight')
                    ->sortable()
                    ->inline()
                    ->extraAttributes(['class' => 'ml-[-140px]'])
                    ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin'])),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->headerActions([CreateAction::make()->visible(fn() => false),]);
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
            'index' => Pages\ListSpotlights::route('/'),
            // 'create' => Pages\CreateSpotlight::route('/create'),
            // 'edit' => Pages\EditSpotlight::route('/{record}/edit'),
        ];
    }
}
