<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\CxoUserWelcomeNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected string $generatedPassword;
    protected function mutateFormDataBeforeCreate(array $data): array
    {   
        $this->generatedPassword = Str::random(8);
        
        $data['password'] = bcrypt($this->generatedPassword); // ✅ auth user assign ho jayega
        return $data;
    }
    protected function afterCreate(): void
    {
        $user = $this->record;
        $user->notify(new CxoUserWelcomeNotification($this->generatedPassword));
        
    }
}
