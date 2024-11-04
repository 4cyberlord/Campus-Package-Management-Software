<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'student', 'guard_name' => 'web']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create student user
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');
    }
}
