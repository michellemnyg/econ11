<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nip' => '198901012019031001', // NIP Admin Tester
            'name' => 'Admin Super econ11',
            'username' => 'admin_econ11',
            'password' => Hash::make('password123'), // Password default
            'level_id' => 1,
        ]);
    }
}
