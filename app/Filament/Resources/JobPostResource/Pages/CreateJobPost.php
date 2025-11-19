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
        $loggedInUser = auth()->user();

        $memberCredit = \App\Models\Member::where('user_id', $loggedInUser->id)->first();
        $memberPlan = \App\Models\Plan::find($memberCredit->plan_id);


        $deduction = match ($memberPlan->name) {
            'Gold' => 2,
            'Silver' => 2,
            'Basic' => 1,
            default => 99, 
        };

        $current_creds = $memberCredit->remaining_credits;


        if ($current_creds >= $deduction) {
            $memberCredit->update([
                'remaining_credits' => $current_creds - $deduction,
            ]);
        }

       
        $allUsers = User::all();

        $recipients = $job->job_status == 1
            ? $allUsers->filter(fn($u) => $u->hasRole('member'))
            : $allUsers->filter(fn($u) => $u->hasRole('super-admin'));

        Notification::make()
            ->title('New Job Created')
            ->body("\"{$job->title}\" has been created.")
            ->icon('heroicon-o-briefcase')
            ->color('success')
            ->sendToDatabase($recipients)
            ->broadcast($recipients);
    }


}
