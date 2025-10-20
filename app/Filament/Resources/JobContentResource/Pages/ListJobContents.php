<?php

namespace App\Filament\Resources\JobContentResource\Pages;

use App\Filament\Resources\JobContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobContents extends ListRecords
{
    protected static string $resource = JobContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
