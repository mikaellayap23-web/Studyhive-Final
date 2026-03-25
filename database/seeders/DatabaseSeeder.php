<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'first_name' => 'Mikaella Rosalia',
            'last_name' => 'Torre',
            'username' => 'MikaellaTorre',
            'email' => 'mikaellayap23@gmail.com',
            'email_verified_at' => now(), 
            'password' => Hash::make('admin123'), 
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // // Create Student account
        User::create([
            'first_name' => 'Kooky Lyann',
            'last_name' => 'Arabia',
            'username' => 'KookyArabia',
            'email' => 'kookyarabia06@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('student123'),
            'remember_token' => Str::random(10),
            'role' => 'student',
            'status' => 'pending', 
            'updated_at' => now(),
        ]);

          // Create Teacher Account
        User::create([
            'first_name' => 'Renz Aaron',
            'last_name' => 'Mendiola',
            'username' => 'RenzAaronMendiola',
            'email' => 'ramendiola418@gmail.com',
            'email_verified_at' => now(), 
            'password' => Hash::make('teacher123'), 
            'remember_token' => Str::random(10),
            'role' => 'teacher',
            'status' => 'active', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}