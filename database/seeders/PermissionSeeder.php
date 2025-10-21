<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Permission = [
            'create Magazine',
            'edit Magazine',
            'delete Magazine',
            'view Magazine',
            'create News',
            'edit News',
            'delete News',
            'view News',
            'create Member',
            'edit Member',
            'delete Member',
            'view Member',
            'create User',
            'edit User',
            'delete User', 
            'view User',
            'create Role',
            'edit Role',
            'delete Role',
            'view Role',
            'create Permission',
            'edit Permission',
            'delete Permission',
            'view Permission',
            // 'create Settings',
            // 'edit Settings',
            // 'delete Settings',  
            // 'view Settings',
            'create Plan',
            'edit Plan',
            'delete Plan',
            'view Plan',
            'create MemberContent',
            'edit MemberContent',
            'delete MemberContent',  
            'view MemberContent',
            'create MemberFeed',
            'edit MemberFeed',
            'delete MemberFeed',  
            'view MemberFeed',
            'create JobPost',
            'edit JobPost',
            'delete JobPost',
            'view JobPost',
            'create Event',
            'edit Event',
            'delete Event',
            'view Event',
        ];

        foreach ($Permission as $permission) {
            // extract model name (the last word of permission string)
            $parts = explode(' ', $permission);
            $model = Str::studly(end($parts)); // e.g. 'user' → 'User'

            Permission::updateOrCreate(
                ['name' => $permission],
                ['model' => $model]
            );
        }
    }
}
