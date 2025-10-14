<?php

namespace App\Filament\Resources\MemberFeedResource\Pages;

use App\Filament\Resources\MemberFeedResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberFeed extends CreateRecord
{
    protected static string $resource = MemberFeedResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
