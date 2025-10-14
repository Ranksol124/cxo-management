<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Models\News;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNews extends ViewRecord
{
    protected static string $resource = NewsResource::class;

    protected static string $view = 'filament.resources.news.view-news';

    public function mount($record): void
    {
        $this->record = News::findOrFail($record);
    }

    public function getTitle(): string
    {
        return "Viewing news: {$this->record->name}";
    }
}
