<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Events\EventCreate;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        

        $data['user_id'] = Auth::user()->id; // ✅ auth user assign ho jayega
        return $data;
    }
   
    protected function afterCreate(): void
    {
        // $recipient = auth()->user();
        
        $user = Auth::user();
        $superAdminId = null;

        if ($user->hasRole('super-admin')) {
            $superAdminId = $user->id;
        }

        $recipients = User::where('id', '!=', $superAdminId)->get();
        Notification::make()
            ->title('New event created!')
            ->body('"' . $this->record->title . '" has been created.')
            ->sendToDatabase($recipients)
            ->broadcast($recipients);
    }
}
