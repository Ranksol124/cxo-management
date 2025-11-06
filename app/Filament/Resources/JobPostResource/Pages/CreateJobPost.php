<?php

namespace App\Filament\Resources\JobPostResource\Pages;

use App\Filament\Resources\JobPostResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateJobPost extends CreateRecord
{
    protected static string $resource = JobPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Assign the logged-in user as creator
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $job = $this->record;
        $allUsers = User::all();

        if ($job->job_status == 1) {
            $recipients = $allUsers->filter(fn($u) => $u->hasRole('member'));
        } else {
            $recipients = $allUsers->filter(fn($u) => $u->hasRole('super-admin'));
        }
        Notification::make()
            ->title('New Job Created')
            ->body("\"{$job->title}\" has been created.")
            ->icon('heroicon-o-briefcase')
            ->color('success')
            ->sendToDatabase($recipients)
            ->broadcast($recipients);
    }
}
