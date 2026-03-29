<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'sukmaukylaamart@gmail.com',
            'password' => Hash::make('UkyLaa2026mart!'),
            'role' => 'admin',
        ]);
    }
}