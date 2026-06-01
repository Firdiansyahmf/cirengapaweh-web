<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test superadmin
        User::firstOrCreate(
            ['email' => 'admin@cirengapaweh.com'],
            [
                'name' => 'Admin Cireng A\'paweh',
                'email' => 'admin@cirengapaweh.com',
                'password' => Hash::make('admin123456'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        // Create test staff
        User::firstOrCreate(
            ['email' => 'staff@cirengapaweh.com'],
            [
                'name' => 'Staff Cireng A\'paweh',
                'email' => 'staff@cirengapaweh.com',
                'password' => Hash::make('staff123456'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );
    }
}
