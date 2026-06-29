<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // Role
            ['name' => 'Role View', 'slug' => 'role.view', 'module' => 'Role', 'status' => true],
            ['name' => 'Role Create', 'slug' => 'role.create', 'module' => 'Role', 'status' => true],
            ['name' => 'Role Edit', 'slug' => 'role.edit', 'module' => 'Role', 'status' => true],
            ['name' => 'Role Delete', 'slug' => 'role.delete', 'module' => 'Role', 'status' => true],

            // User
            ['name' => 'User View', 'slug' => 'user.view', 'module' => 'User', 'status' => true],
            ['name' => 'User Create', 'slug' => 'user.create', 'module' => 'User', 'status' => true],
            ['name' => 'User Edit', 'slug' => 'user.edit', 'module' => 'User', 'status' => true],
            ['name' => 'User Delete', 'slug' => 'user.delete', 'module' => 'User', 'status' => true],

        ];

        foreach ($permissions as $permission) {

            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );

        }
    }
}