<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();


        /*
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
        */

        if ($this->record->user) {

            /*
            $rolesToAssign = [];

            if ($planData && $planData->roles->isNotEmpty()) {
                $rolesToAssign = array_merge(
                    $rolesToAssign,
                    $planData->roles->pluck('name')->toArray()
                );
            } else {
                $roleName = $planSlug . '-user';

                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => 'web']
                );

                $rolesToAssign[] = $role->name;
            }

            if (!empty($data['roles'])) {
                $rolesToAssign = array_merge($rolesToAssign, $data['roles']);
            }

            $this->record->user->syncRoles(array_unique($rolesToAssign));
            */


            $memberRole = Role::firstOrCreate(
                ['name' => 'member', 'guard_name' => 'web']
            );

            $this->record->user->syncRoles([$memberRole->name]);

            $user = Auth::user();
            $superAdminId = null;

            if ($user->hasRole('super-admin')) {
                $superAdminId = $user->id;
            }

            $recipients = User::where('id', '!=', $superAdminId)->get();
            Notification::make()
                ->title('New member created')
                ->body('"' . $this->record->full_name . '" has been created.')
                ->sendToDatabase($recipients)
                ->broadcast($recipients);
        }
    }
}
