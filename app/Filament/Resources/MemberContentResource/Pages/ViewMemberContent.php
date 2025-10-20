<?php

namespace App\Filament\Resources\MemberContentResource\Pages;

use App\Filament\Resources\MemberContentResource;
use App\Models\Member;
use App\Models\MemberContent;
use App\Models\MemberContentAttachment;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberContent extends ViewRecord
{
    protected static string $resource = MemberContentResource::class;
    protected static string $view = 'filament.resources.members.view-member-content';
    
    public function mount($record): void
    {
        
        $this->record = MemberContent::with(['attachments', 'member'])->find($record);
    }

    public function getTitle(): string
    {
        return "Viewing member content: {$this->record->name}";
    }
}
