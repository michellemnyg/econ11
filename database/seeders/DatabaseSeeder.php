<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder secara berurutan
        $this->call([
            LevelSeeder::class,
            UnitSeeder::class,
            PnsSeeder::class,
            TopikSeeder::class,
            UserSeeder::class,
            ConsultationSeeder::class,
        ]);
    }
}
