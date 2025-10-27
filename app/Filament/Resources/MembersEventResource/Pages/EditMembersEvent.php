<?php

namespace App\Filament\Resources\MembersEventResource\Pages;

use App\Filament\Resources\MembersEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembersEvent extends EditRecord
{
    protected static string $resource = MembersEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
