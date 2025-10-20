<?php

namespace App\Filament\Resources\JobContentResource\Pages;

use App\Filament\Resources\JobContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobContent extends EditRecord
{
    protected static string $resource = JobContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
