<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $studentRole = Role::create(['name' => 'student', 'guard_name' => 'web']);

        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create student
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign roles
        $superAdmin->assignRole($superAdminRole);
        $admin->assignRole($adminRole);
        $manager->assignRole($managerRole);
        $student->assignRole($studentRole);
    }
}
