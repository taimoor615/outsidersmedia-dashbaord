<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mixbloom.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'timezone' => 'America/New_York',
        ]);

        // Create Team Member 1
        User::create([
            'name' => 'John Doe',
            'email' => 'john@mixbloom.com',
            'password' => Hash::make('password'),
            'role' => 'team',
            'status' => 'active',
            'timezone' => 'America/New_York',
        ]);

        // Create Team Member 2
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@mixbloom.com',
            'password' => Hash::make('password'),
            'role' => 'team',
            'status' => 'active',
            'timezone' => 'America/Los_Angeles',
        ]);

        // Create Inactive Team Member (for testing)
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@mixbloom.com',
            'password' => Hash::make('password'),
            'role' => 'team',
            'status' => 'inactive',
            'timezone' => 'America/Chicago',
        ]);
    }
}
