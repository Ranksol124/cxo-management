<?php



namespace App\Filament\Resources\JobContentResource\Pages;

use App\Filament\Resources\JobContentResource;
use App\Models\JobMembers;
use Filament\Resources\Pages\ViewRecord;

class ViewJobContent extends ViewRecord
{
    protected static string $resource = JobContentResource::class;

    protected static string $view = 'filament.resources.job-member.view-job-content';

    public function mount($record): void
    {
        $this->record = JobMembers::with(['job', 'member'])->find($record);
    }

    public function getTitle(): string
    {
        return "Viewing Job Content: {$this->record->member->name}";
    }
}
