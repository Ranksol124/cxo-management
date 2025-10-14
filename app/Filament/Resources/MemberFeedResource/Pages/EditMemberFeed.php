<?php

namespace App\Filament\Resources\MemberFeedResource\Pages;

use App\Filament\Resources\MemberFeedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberFeed extends EditRecord
{
    protected static string $resource = MemberFeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
