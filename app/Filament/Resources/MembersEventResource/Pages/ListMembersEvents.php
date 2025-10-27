<?php

namespace App\Filament\Resources\MembersEventResource\Pages;

use App\Filament\Resources\MembersEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMembersEvents extends ListRecords
{
    protected static string $resource = MembersEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
