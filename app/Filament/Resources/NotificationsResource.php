<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use App\Filament\Resources\NotificationsResource\Pages;
use App\Models\Notifications;
use Filament\Notifications\Notification;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;
class NotificationsResource extends Resource
{

    protected static ?string $model = Notifications::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('template_name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('type')
                ->options([
                    'event' => 'Event',
                    'job_posts' => 'Job Post',
                    'news' => 'News',
                    'magzines' => 'Magzines',
                ])
                ->required(),

            Forms\Components\TextInput::make('tags')
                ->nullable()
                ->maxLength(255),

            Forms\Components\TextInput::make('Title')
                ->nullable()
                ->maxLength(255),

            Forms\Components\Radio::make('options')
                ->label('Notification Channel')
                ->options([
                    'email' => 'Email',
                    'mobile_app' => 'Mobile App',
                ])
                ->inline()
                ->required(),

            Forms\Components\RichEditor::make('description')
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('template_name'),

                SelectColumn::make('type')
                    ->url(null)
                    ->options([
                        'event' => 'Event',
                        'job_posts' => 'Job Post',
                        'news' => 'News',
                        'magzines' => 'Magzines',
                    ])
                    ->extraAttributes(['class' => 'mr-10'])
                    ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin'])),

                ToggleColumn::make('options.email')
                    ->label('Email')
                    ->getStateUsing(fn($record) => false)
                    ->disabled(),

                ToggleColumn::make('options.mobile_app')
                    ->label('Mobile App')
                    ->getStateUsing(fn($record) => false)
                    ->disabled(),





                TextColumn::make('Title'),
                TextColumn::make('tags'),
                TextColumn::make('description')
                    ->html()
                    ->label('Description'),
            ])

            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('sendEmails')
                    ->label('Send Emails to All Users')
                    ->action(function ($record) {
                        $users = \App\Models\User::all();

                        foreach ($users as $user) {
                            $user->notify(new \App\Notifications\SendNotification(
                                $record->Title,
                                $record->description,
                                now()->toDateString(),   
                                $record->type           
                            ));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Emails Sent')
                            ->body('Emails with type "' . ucfirst($record->type) . '" have been sent to all users!')
                            ->success()
                            ->send();
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
            // Add relation managers here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotifications::route('/create'),
            'edit' => Pages\EditNotifications::route('/{record}/edit'),
        ];
    }
}
