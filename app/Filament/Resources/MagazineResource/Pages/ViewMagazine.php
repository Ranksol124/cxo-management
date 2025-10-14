<?php

namespace App\Filament\Resources\MagazineResource\Pages;

use App\Filament\Resources\MagazineResource;
use App\Models\Magazine;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMagazine extends ViewRecord
{
    protected static string $resource = MagazineResource::class;

    protected static string $view = 'filament.resources.magazines.view-magazine';

    public function mount($record): void
    {
        $this->record = Magazine::findOrFail($record);
    }

    public function getTitle(): string
    {
        return "Viewing magazine: {$this->record->name}";
    }
}
