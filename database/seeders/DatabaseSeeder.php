<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\JobPost;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\FilamentTestUsersSeeder;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // FilamentTestUsersSeeder::class,
            //PermissionSeeder::class,
            Event::factory()->count(150)->create(),
            //JobPost::factory()->count(10)->create()
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
