<?php

namespace App\Filament\Resources\SpotlightResource\Pages;

use App\Filament\Resources\SpotlightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpotlight extends EditRecord
{
    protected static string $resource = SpotlightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
