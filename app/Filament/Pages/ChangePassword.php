<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Change Password';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.change-password';
    
    public ?array $data = [];
    public function mount()
    {
        if (session()->has('success')) {
            Notification::make()
                ->title(session('success'))
                ->success()
                ->send();
        }
    }
    public function form(Form $form): Form
    {
        return $form->schema([
            // TextInput::make('old_password')->password()->required(),
            TextInput::make('new_password')->password()->required()->minLength(8),
            TextInput::make('new_password_confirmation')->password()->same('new_password'),
        ])->statePath('data');
    }

    public function save(): void
    {
        $this->form->validate();

        $user = Auth::user();

        //check if old password is not correct
        // if (! Hash::check($this->data['old_password'], $user->password)) {
        //     Notification::make()->title('Old password is incorrect!')->danger()->send();
        //     return;
        // }

        $user->password = Hash::make($this->data['new_password']);
        $user->save();
        
        Notification::make()->title('Password updated successfully!')->success()->send();

        $this->data = [];
    }

}
