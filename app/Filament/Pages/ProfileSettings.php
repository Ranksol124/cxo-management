<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProfileSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $navigationGroup = 'Settings';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 98;
    protected static string $view = 'filament.pages.profile-settings';

    public ?array $data = [];

    //mount hook to show forms fields value
    public function mount(): void
    {
        $this->form->fill(Auth::user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Full Name')
                    ->maxLength(255),

                TextInput::make('contact')
                    ->label('contact')
                    ->maxLength(255),

                Textarea::make('address')->label('Address')->columnSpanFull(),

                FileUpload::make('profile_picture')->label('Profile Picture')
                    ->image()->disk('public')
                    ->directory('avatars')->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->form->validate();

        $user = Auth::user();
        $user->name = $this->data['name'];
        $user->contact = $this->data['contact'];
        $user->address = $this->data['address'];

        if (!empty($this->data['profile_picture'])) {

            //O index is not available so I need tempID from array
            $tempFileId = array_key_first($this->data['profile_picture']);
            //get temporary file path with tempID
            $temporaryFile = $this->data['profile_picture'][$tempFileId]; // first file
            //making new original path saving into avatars folder
            $path = $temporaryFile->store('avatars', 'public');
            //saving into database
            $user->profile_picture = $path;
        }

        $user->save();

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();
    }
}
