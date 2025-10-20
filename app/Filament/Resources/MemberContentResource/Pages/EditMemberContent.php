<?php

namespace App\Filament\Resources\MemberContentResource\Pages;

use App\Filament\Resources\MemberContentResource;
use App\Models\MemberContentAttachment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberContent extends EditRecord
{
    protected static string $resource = MemberContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $data = $this->form->getState();

        // Delete old attachments first
        $this->record->attachments()->delete();

        // Save new attachments
        foreach ($data['content_attachments_files'] as $path) {
            $this->record->attachments()->create([
                'file_path' => $path,
            ]);
        }
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content_attachments_files'] = $this->record
            ->attachments()
            ->pluck('file_path')
            ->toArray();

        return $data;
    }
}
