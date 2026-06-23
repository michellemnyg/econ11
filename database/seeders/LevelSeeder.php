<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['id' => 1, 'nama_level' => 'Super Admin'],
            ['id' => 2, 'nama_level' => 'Operator'],
            ['id' => 3, 'nama_level' => 'Ketua Tim'],
            ['id' => 4, 'nama_level' => 'Narasumber'],
        ];
        DB::table('levels')->insert($levels);
    }
}
