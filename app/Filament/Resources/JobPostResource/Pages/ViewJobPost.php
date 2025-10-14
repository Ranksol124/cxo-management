<?php

namespace App\Filament\Resources\JobPostResource\Pages;

use App\Filament\Resources\JobPostResource;
use App\Models\JobPost;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJobPost extends ViewRecord
{
    protected static string $resource = JobPostResource::class;
    protected static string $view = 'filament.resources.job-posts.view-job-post';

    public function mount($record): void
    {
        $this->record = JobPost::findOrFail($record);
    }

    public function getTitle(): string
    {
        return "Viewing job post: {$this->record->name}";
    }
}
