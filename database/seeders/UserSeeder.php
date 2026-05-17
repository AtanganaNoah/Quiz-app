<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@qcm.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Teacher
        User::create([
            'name'     => 'teacher',
            'email'    => 'teacher@qcm.com',
            'password' => Hash::make('password'),
            'role'     => 'teacher',
        ]);

        // Students
        User::create([
            'name'     => 'Étudiant Alice',
            'email'    => 'alice@qcm.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        User::create([
            'name'     => 'Étudiant Bob',
            'email'    => 'bob@qcm.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);
    }
}