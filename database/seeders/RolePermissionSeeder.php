<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create roles
        // $admin = Role::create(['name' => 'admin']);
        // $superadmin = Role::create(['name' => 'super-admin']);
        // $editor = Role::create(['name' => 'editor']);
        // $user = Role::create(['name' => 'user']);
        // $enterpriseUser = Role::create(['name' => 'enterprise-user']);
        // $goldUser = Role::create(['name' => 'gold-user']);
        // $silverUser = Role::create(['name' => 'silver-user']);

        // Create permissions
        Permission::create(['name' => 'create magazines']);
        Permission::create(['name' => 'edit magazines']);
        Permission::create(['name' => 'delete magazines']);
        Permission::create(['name' => 'view magazines']);
        Permission::create(['name' => 'create news']);
        Permission::create(['name' => 'edit news']);
        Permission::create(['name' => 'delete news']);
        Permission::create(['name' => 'view news']);
        Permission::create(['name' => 'create members']);
        Permission::create(['name' => 'edit members']);
        Permission::create(['name' => 'delete members']);
        Permission::create(['name' => 'view members']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'delete roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'edit permissions']);
        Permission::create(['name' => 'delete permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create settings']);
        Permission::create(['name' => 'edit settings']);
        Permission::create(['name' => 'delete settings']);
        Permission::create(['name' => 'view settings']);
        Permission::create(['name' => 'create plans']);
        Permission::create(['name' => 'edit plans']);
        Permission::create(['name' => 'delete plans']);
        Permission::create(['name' => 'view plans']);
    }
}
