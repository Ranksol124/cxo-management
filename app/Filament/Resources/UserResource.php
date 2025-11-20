<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Section;
use App\Filament\Traits\HasResourcePermissions;
use App\Models\Member;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\ViewColumn;
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'CXO Users';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')->extraAttributes([
                    'class' => 'filament-custom-section',
                ])->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('email')
                        ->email()
                        ->required(),

                    Select::make('roles')
                        ->multiple()
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->whereNotIn('name', [
                                'enterprise-user',
                                'gold-user',
                                'silver-user',
                            ])
                        )
                        ->preload()
                        ->label('Roles')
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = Auth::user();

                if ($user->hasRole('super-admin')) {
                    // Super-admin → sab dekh sakta hai including apna
                    return $query;
                }

                // Non-super-admin users:
                $query->where('id', '!=', $user->id) // apna record exclude
                    ->whereDoesntHave('roles', function ($q) {
                        $q->where('name', 'super-admin'); // super-admin users hide
                    });
            })
            ->recordUrl(false)
            ->contentGrid([
                'md' => 3,
                'lg' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    // Stack::make([
                    //     ImageColumn::make('profile_picture')
                    //         ->extraImgAttributes(['class' => 'rounded-t-md !h-40 !w-40'])
                    //         ->defaultImageUrl(asset('icons/no_icon.svg')), // fallback image
                    // ])->extraAttributes(['class' => 'mb-4 text-center']),
       ViewColumn::make('image')->view('tables.columns.user-image'),
                    Stack::make([
                        Tables\Columns\TextColumn::make('name')->prefix('Name: ')->limit(50)->extraAttributes(['class' => 'font-semibold']),
                        Tables\Columns\TextColumn::make('email')->prefix('Email: ')->extraAttributes(['class' => 'mb-1 mt-1']),
                        BadgeColumn::make('member.plan.name')
                            ->prefix('Plan: ')
                            ->colors(['primary' => 'Enterprise', 'danger' => 'Gold', 'warning' => 'Silver'])->extraAttributes(['class' => 'mb-1']),

                        TextColumn::make('roles.name')
                            ->getStateUsing(
                                fn($record) =>
                                $record->roles->pluck('name')->unique()->toArray() ?? []
                            )
                            ->badge()
                            ->separator(', ')
                            ->color(fn($state) => match (true) {
                                str_contains($state, 'enterprise') => 'success',
                                str_contains($state, 'gold')       => 'danger',
                                str_contains($state, 'silver')     => 'warning',
                                str_contains($state, 'admin')      => 'info',
                                default                            => 'gray',
                            }),

                    ]),

                ])->extraAttributes([
                    'class' => 'p-1 shadow-sm bg-white',
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->doesntHave('member'); // ✅ sirf woh users jo members table me nahi
    }
}
