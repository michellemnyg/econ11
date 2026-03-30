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
            TopikSeeder::class,
            UserSeeder::class, // File yang kamu buat sebelumnya tidak perlu dihapus
        ]);
    }
}
