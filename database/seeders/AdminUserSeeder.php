<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::SUPER_ADMIN,
        ]);

        // Create Editor
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@admin.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::EDITOR,
        ]);

        // Create Reporter
        User::create([
            'name' => 'Reporter User',
            'email' => 'reporter@admin.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::REPORTER,
        ]);
    }
}
