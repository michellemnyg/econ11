<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopikSeeder extends Seeder
{
    public function run(): void
    {
        $topiks = [
            ['id' => 1, 'nama_topik' => 'Penyusunan dan penetapan kebutuhan'],
            ['id' => 2, 'nama_topik' => 'Pengadaan'],
            ['id' => 3, 'nama_topik' => 'Pangkat dan Jabatan'],
            ['id' => 4, 'nama_topik' => 'Pengembangan karier'],
            ['id' => 5, 'nama_topik' => 'Pola karier'],
            ['id' => 6, 'nama_topik' => 'Promosi'],
            ['id' => 7, 'nama_topik' => 'Mutasi'],
            ['id' => 8, 'nama_topik' => 'Penilaian kinerja'],
            ['id' => 9, 'nama_topik' => 'Penggajian dan tunjangan'],
            ['id' => 10, 'nama_topik' => 'Penghargaan'],
            ['id' => 11, 'nama_topik' => 'Disiplin'],
            ['id' => 12, 'nama_topik' => 'Pemberhentian'],
            ['id' => 13, 'nama_topik' => 'Jaminan pensiun dan jaminan hari tua'],
            ['id' => 14, 'nama_topik' => 'Perlindungan'],
            ['id' => 15, 'nama_topik' => 'Manajemen PPPK'],
            ['id' => 16, 'nama_topik' => 'Aplikasi Layanan Kepegawaian'],
            ['id' => 17, 'nama_topik' => 'Arsip Kepegawaian'],
        ];
        DB::table('topiks')->insert($topiks);
    }
}
