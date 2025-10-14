<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class FilamentTestUsersSeeder extends Seeder
{
    public function run()
    {
        // create roles
        $roles = ['super-admin', 'admin', 'enterprise-user', 'gold-user', 'silver-user'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // super admin
        $super = User::firstOrCreate(
            ['email' => 'super@test.local'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $super->syncRoles(['super-admin']);

        // admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.local'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $admin->syncRoles(['admin']);

        // gold member
        $gold = User::firstOrCreate(
            ['email' => 'gold@test.local'],
            ['name' => 'Gold Member', 'password' => Hash::make('password')]
        );
        $gold->syncRoles(['gold-user']);

        // enterprise member
        $ent = User::firstOrCreate(
            ['email' => 'ent@test.local'],
            ['name' => 'Enterprise Member', 'password' => Hash::make('password')]
        );
        $ent->syncRoles(['enterprise-user']);

        // silver member
        $silver = User::firstOrCreate(
            ['email' => 'silver@test.local'],
            ['name' => 'Silver Member', 'password' => Hash::make('password')]
        );
        $silver->syncRoles(['silver-user']);
    }
}
