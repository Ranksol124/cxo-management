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
        $data = $this->form->getState();
        foreach ($data['content_attachments_files'] as $attachment) {
            MemberContentAttachment::create([
                'member_content_id' => $this->record->id,
                'file_path' => $attachment
            ]);
        }
    }
}
