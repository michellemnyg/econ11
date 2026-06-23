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
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'nip',
                'nama' => 'nama',
                'nama_jabatan' => 'Pegawai Dummy',
                'unor_induk_nama' => 'Instansi Dummy',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'superadmin',
                'nama' => 'Super Admin',
                'nama_jabatan' => 'Super Admin',
                'unor_induk_nama' => 'BKN',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'ketuatim',
                'nama' => 'Ketua Tim',
                'nama_jabatan' => 'Ketua Tim',
                'unor_induk_nama' => 'BKN',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'operator',
                'nama' => 'Operator',
                'nama_jabatan' => 'Operator',
                'unor_induk_nama' => 'BKN',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'narasumber',
                'nama' => 'Narasumber',
                'nama_jabatan' => 'Narasumber',
                'unor_induk_nama' => 'BKN',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'narasumber2',
                'nama' => 'Narasumber Dua',
                'nama_jabatan' => 'Narasumber',
                'unor_induk_nama' => 'BKN',
            ],
            [
                'id' => Str::uuid(),
                'nip_baru' => 'narasumber3',
                'nama' => 'Narasumber Tiga',
                'nama_jabatan' => 'Narasumber',
                'unor_induk_nama' => 'BKN',
            ]
        ];

        DB::table('pns')->insert($pns);
    }
}
