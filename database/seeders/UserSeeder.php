<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nip' => 'superadmin',
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('pass'),
                'level_id' => 1,
                'unit_id' => 1,
            ],
            [
                'nip' => 'operator',
                'name' => 'Operator',
                'username' => 'operator',
                'password' => Hash::make('pass'),
                'level_id' => 2,
                'unit_id' => 1,
            ],
            [
                'nip' => 'ketuatim',
                'name' => 'Ketua Tim',
                'username' => 'ketuatim',
                'password' => Hash::make('pass'),
                'level_id' => 3,
                'unit_id' => 1,
            ],
            [
                'nip' => 'narasumber',
                'name' => 'Narasumber',
                'username' => 'narasumber',
                'password' => Hash::make('pass'),
                'level_id' => 4,
                'unit_id' => 1,
            ],
            [
                'nip' => 'narasumber2',
                'name' => 'Narasumber Dua',
                'username' => 'narasumber2',
                'password' => Hash::make('pass'),
                'level_id' => 4,
                'unit_id' => 1,
            ],
            [
                'nip' => 'narasumber3',
                'name' => 'Narasumber Tiga',
                'username' => 'narasumber3',
                'password' => Hash::make('pass'),
                'level_id' => 4,
                'unit_id' => 1,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
