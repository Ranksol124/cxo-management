<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;
    protected static string $view = 'filament.resources.events.view-event';

    public function mount($record): void
    {
        $this->record = Event::findOrFail($record);
    }

    public function getTitle(): string
    {
        return "Viewing event: {$this->record->name}";
    }
}
