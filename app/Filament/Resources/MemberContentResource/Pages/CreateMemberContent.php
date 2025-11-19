<?php

namespace App\Filament\Resources\MemberContentResource\Pages;

use App\Filament\Resources\MemberContentResource;
use App\Models\MemberContentAttachment;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMemberContent extends CreateRecord
{
    protected static string $resource = MemberContentResource::class;

    function mutateFormDataBeforeCreate(array $data): array
    {
        $data['member_id'] = auth()->user()->member->id;
        return $data;
    }

    function afterCreate(): void
    {

        $job = $this->record;
        $loggedInUser = auth()->user();

        $memberCredit = \App\Models\Member::where('user_id', $loggedInUser->id)->first();
        $memberPlan = \App\Models\Plan::find($memberCredit->plan_id);

        $deduction = match ($memberPlan->name) {
            'Gold' => 3,
            'Silver' => 2,
            'Basic' => 2,
            default => 99,
        };

        $current_creds = $memberCredit->remaining_credits;


        if ($current_creds >= $deduction) {
            $memberCredit->update([
                'remaining_credits' => $current_creds - $deduction,
            ]);
        }




        $data = $this->form->getState();
        foreach ($data['content_attachments_files'] as $attachment) {
            MemberContentAttachment::create([
                'member_content_id' => $this->record->id,
                'file_path' => $attachment
            ]);
        }
    }
}
