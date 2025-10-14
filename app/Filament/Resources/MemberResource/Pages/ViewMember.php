<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Member;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.resources.members.view-member';


    public function mount($record): void
    {
        $this->record = Member::with(['plan','enterpriseDetails','goldDetails','silverDetails'])->findOrFail($record);
    }

    public function getTitle(): string
    {
        return "Viewing Member: {$this->record->name}";
    }
}
