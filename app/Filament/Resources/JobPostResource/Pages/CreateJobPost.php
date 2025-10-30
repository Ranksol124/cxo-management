<?php

namespace App\Filament\Resources\JobPostResource\Pages;

use App\Filament\Resources\JobPostResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
class CreateJobPost extends CreateRecord
{
    protected static string $resource = JobPostResource::class;

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
            ->title('New jobs created')
            ->body('"' . $this->record->title . '" has been created. Check it out!')
            ->sendToDatabase($recipients)
            ->broadcast($recipients);
    }
}
