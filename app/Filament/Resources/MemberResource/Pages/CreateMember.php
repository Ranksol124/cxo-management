<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Spatie\Permission\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    // function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data);
    // }
    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $planID = $data['plan_id'] ?? null;
        $planData = \App\Models\Plan::with('roles')->find($planID);
        $planSlug = $planData->slug ?? null;

        if ($planSlug === 'enterprise') {
            $this->record->enterpriseDetails()->create($data);
        } elseif ($planSlug === 'gold') {
            $this->record->goldDetails()->create($data);
        } elseif ($planSlug === 'silver') {
            $this->record->silverDetails()->create($data);
        }
        if ($this->record->user) {
            $rolesToAssign = [];

            // ✅ get roles directly from plan
            if ($planData && $planData->roles->isNotEmpty()) {
                $rolesToAssign = array_merge(
                    $rolesToAssign,
                    $planData->roles->pluck('name')->toArray()
                );
            } else {
                $roleName = $planSlug . '-user';

                // create role if not exists
                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => 'web']
                );

                $rolesToAssign[] = $role->name;
            }

            // add any roles selected in form
            if (!empty($data['roles'])) {
                $rolesToAssign = array_merge($rolesToAssign, $data['roles']);
            }

            // assign all (avoid duplicates)
            $this->record->user->syncRoles(array_unique($rolesToAssign));
        }
    }
}
