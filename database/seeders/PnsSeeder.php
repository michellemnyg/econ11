<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PnsSeeder extends Seeder
{
    public function run(): void
    {
        $pns = [
            [
                'id' => Str::uuid(),
                'nip_baru' => '198901012019031001',
                'nama' => 'Ahmad Fauzi',
                'nama_jabatan' => 'Analis Kepegawaian',
                'unor_induk_nama' => 'BKD Provinsi Jawa Tengah',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => '197912302005011000',
                'nama' => 'Rina Kurniasih',
                'nama_jabatan' => 'Pranata Komputer Ahli Muda',
                'unor_induk_nama' => 'BKD Kota Bandung',
            ]
        ];

        DB::table('pns')->insert($pns);
    }
}
