<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create();
        User::factory()->create([
            'name' => 'Abdelrahman Abbas',
            'email' => 'abdelrahman@example.com',
        ]);

        // Create an admin user
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

    }
}
