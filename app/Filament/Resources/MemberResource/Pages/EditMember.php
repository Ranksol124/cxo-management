<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Spatie\Permission\Models\Role;
use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function afterSave(): void
    {
        $data = $this->form->getState();
        $planID = $data['plan_id'] ?? null;
        $planData = \App\Models\Plan::with('roles')->find($planID);
        $planSlug = $planData->slug ?? null;

        if ($planSlug === 'enterprise') {
            $this->record->enterpriseDetails()->updateOrCreate([], $data);
        } elseif ($planSlug === 'gold') {
            $this->record->goldDetails()->updateOrCreate([], $data);
        } elseif ($planSlug === 'silver') {
            $this->record->silverDetails()->updateOrCreate([], $data);
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
        if ($data['password'] != "") {
            $user = $this->record->user()->first(); // relation fresh load
            // dd($user->id, $user->email, $data['password'],bcrypt($data['password']));
            // if ($user) {
            //     $user->forceFill([
            //         'password' => bcrypt($data['password']),
            //     ])->save();
            DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => bcrypt($data['password'])]);
            
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
