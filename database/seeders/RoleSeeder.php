<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Super Administrator',
                'status' => true,
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator',
                'status' => true,
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Teacher',
                'status' => true,
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Student',
                'status' => true,
            ],
            [
                'name' => 'Receptionist',
                'slug' => 'receptionist',
                'description' => 'Receptionist',
                'status' => true,
            ],
            [
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Accountant',
                'status' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}