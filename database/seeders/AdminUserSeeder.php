<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create the admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => '2CA BUSINESS',
                'password' => bcrypt('test12345'),
                'role_id' => 1
            ]
        );

        // Assign the admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        $this->command->info('Admin user created: admin@admin.com | test12345');
    }
}
