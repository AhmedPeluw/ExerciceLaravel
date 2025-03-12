<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles creation
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Permissions creation
        Permission::create(['name' => 'manage payments']);
        Permission::create(['name' => 'view all users']);

        // Assignation
        $admin->givePermissionTo(['manage payments', 'view all users']);
        $user->givePermissionTo(['manage payments']);
    }
}
