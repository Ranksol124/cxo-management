<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationLabel = 'Roles & Permissions';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Role Details')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Role Name')
                        ->required()
                        ->unique(ignoreRecord: true)->columnSpanFull(),

                    Grid::make(3)
                        ->schema(self::buildPermissionSections())
                        ->columns(3)->extraAttributes(['class' => 'border p-4 rounded-lg mt-6']),
                ])
            ]);
    }

    protected static function buildPermissionSections(): array
    {
        // Group permissions by model column
        $permissions = Permission::query()
            ->orderBy('model')
            ->orderBy('name')
            ->get()
            ->groupBy(fn($perm) => $perm->model ?? 'General');

        $sections = [];

        // In a real-world scenario, you might want to limit the number of sections
        // shown in a single row to 3, but this code will iterate over ALL groups.
        // If you have more than 3 groups, they will wrap to the next row, which is the desired behavior for a Grid.

        foreach ($permissions as $model => $items) {
            $sections[] = Forms\Components\CheckboxList::make("permissions_{$model}")

                ->options($items->pluck('name', 'name')->toArray())
                ->columns(1)
                // Make the 'Select all' toggle appear
                ->bulkToggleable();
        }

        return $sections;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Role'),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Total Permissions')
                    ->sortable(),
            ])
             ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
