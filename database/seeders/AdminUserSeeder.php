<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@webgizi.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@webgizi.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create additional admin if needed
        User::firstOrCreate(
            ['email' => 'superadmin@webgizi.com'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@webgizi.com',
                'password' => Hash::make('superpassword123'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create test regular user
        User::firstOrCreate(
            ['email' => 'user@webgizi.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@webgizi.com',
                'password' => Hash::make('userpassword123'),
                'role' => User::ROLE_USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Default admin users created successfully!');
        $this->command->info('Admin Login: admin@webgizi.com / password123');
        $this->command->info('Super Admin Login: superadmin@webgizi.com / superpassword123');
        $this->command->info('User Login: user@webgizi.com / userpassword123');
    }
}
