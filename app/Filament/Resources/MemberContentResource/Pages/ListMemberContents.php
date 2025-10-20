<?php

namespace App\Filament\Resources\MemberContentResource\Pages;

use App\Filament\Resources\MemberContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberContents extends ListRecords
{
    protected static string $resource = MemberContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Content'), // ✅ custom label
        ];
    }
}
