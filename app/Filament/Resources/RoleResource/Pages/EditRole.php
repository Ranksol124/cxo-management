<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
    protected array $permissionData = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissionData = collect($data)
            ->filter(fn($_, $key) => str_starts_with($key, 'permissions_'))
            ->flatMap(fn($permissions) => $permissions)
            ->values()
            ->all();

        return collect($data)
            ->reject(fn($_, $key) => str_starts_with($key, 'permissions_'))
            ->toArray();
    }

    protected function afterSave(): void
    {
        if (!empty($this->permissionData)) {
            $this->record->syncPermissions($this->permissionData);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $allPermissions = Permission::query()
            ->orderBy('model')
            ->orderBy('name')
            ->get()
            ->groupBy(fn($perm) => $perm->model ?? 'General');

        foreach ($allPermissions as $model => $permissions) {
            $data["permissions_{$model}"] = $this->record
                ->permissions()
                ->whereIn('name', $permissions->pluck('name'))
                ->pluck('name')
                ->toArray();
        }

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
