<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Narasumber;

class NarasumberSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Budi Santoso, S.Kom., M.Si.', 'unit' => 'Tim Manajemen Kinerja'],
            ['nama' => 'Siti Aminah, S.H., M.H.', 'unit' => 'Tim Pengadaan & Kepangkatan'],
            ['nama' => 'Andi Wijaya, S.E.', 'unit' => 'Tim Disiplin & Hukum'],
            ['nama' => 'Rina Melati, S.AP.', 'unit' => 'Tim Pengembangan Karir'],
            ['nama' => 'Dr. Hendra Saputra', 'unit' => 'Tim Pensiun & Mutasi'],
            ['nama' => 'Nisa Fadilah, S.STP.', 'unit' => 'Tim Fasilitasi PPPK']
        ];

        Narasumber::insert($data);
    }
}
