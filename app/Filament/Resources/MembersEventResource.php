<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembersEventResource\Pages;
use App\Filament\Resources\MembersEventResource\RelationManagers;
use App\Models\EventMembers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use App\Services\CvMailerService;
class MembersEventResource extends Resource
{
    protected static ?string $model = EventMembers::class;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?int $navigationSort = -99;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('super-admin');
    }
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
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->hasRole(['member', 'enterprise-member', 'silver-member', 'gold-member'])) {
                    $query->where('member_id', Auth::user()->member->id);
                } else {
                    return $query;
                }
            })
            ->columns([
                TextColumn::make('member.full_name')
                    ->label('Name')
                    ->extraAttributes(['class' => 'ml-[-140px]'])
                    ->limit(50),

                TextColumn::make('member.email')->label('Email')->extraAttributes(['class' => 'ml-[-140px]'])->limit(50),
                Tables\Columns\TextColumn::make('event.title')->label(label: 'Event Title')->extraAttributes(['class' => 'ml-[-240px]'])->limit(50),

                SelectColumn::make('status')
                    ->options([
                        1 => 'Approved',
                        0 => 'Rejected',
                    ])
                    ->afterStateUpdated(function ($state, $record) {
                        $userEmail = $record->member->email;
                        $statusText = $state == 1 ? 'Approved' : 'Rejected';

                        (new CvMailerService())->SendEventStatus($userEmail, $statusText);
                    })
                    ->label('Status')
                    ->extraAttributes(['class' => 'ml-[-140px]'])
                    ->visible(fn() => Auth::user()->hasAnyRole(['super-admin', 'admin'])),




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

            ])

            ->bulkActions([

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
            'index' => Pages\ListMembersEvents::route('/'),
            // 'create' => Pages\CreateMembersEvent::route('/create'),
            // 'edit' => Pages\EditMembersEvent::route('/{record}/edit'),
        ];
    }
}
