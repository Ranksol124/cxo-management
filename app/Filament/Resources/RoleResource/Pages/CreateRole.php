<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
    protected array $permissionData = [];
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        // Remove permission_* fields before saving role
        $this->permissionData = collect($data)
            ->filter(fn($_, $key) => str_starts_with($key, 'permissions_'))
            ->flatMap(fn($permissions) => $permissions)
            ->values()
            ->all();

        return collect($data)
            ->reject(fn($_, $key) => str_starts_with($key, 'permissions_'))
            ->toArray();
    }

    protected function afterCreate(): void
    {
        if (!empty($this->permissionData)) {
            $this->record->syncPermissions($this->permissionData);
        }
    }
}
