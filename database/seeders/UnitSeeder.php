<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['id' => 1, 'nama_unit' => 'Kantor Regional XI BKN', 'parent' => 0, 'id_kantor' => 1],
            ['id' => 2, 'nama_unit' => 'Bagian Tata Usaha', 'parent' => 1, 'id_kantor' => 1],
            ['id' => 3, 'nama_unit' => 'Sub Bagian Umum', 'parent' => 2, 'id_kantor' => 1],
            ['id' => 4, 'nama_unit' => 'Sub Bagian Kepegawaian', 'parent' => 2, 'id_kantor' => 1],
            ['id' => 5, 'nama_unit' => 'Bidang Informasi Kepegawaian', 'parent' => 1, 'id_kantor' => 1],
            ['id' => 6, 'nama_unit' => 'Seksi Pengolahan Data dan Diseminasi Informasi Kepegawaian', 'parent' => 5, 'id_kantor' => 1],
            ['id' => 7, 'nama_unit' => 'Seksi Pemanfaatan Teknologi Informasi', 'parent' => 5, 'id_kantor' => 1],
            ['id' => 8, 'nama_unit' => 'Bidang Mutasi & Status Kepegawaian', 'parent' => 1, 'id_kantor' => 1],
            ['id' => 9, 'nama_unit' => 'Bidang Pengangkatan & Pensiun', 'parent' => 1, 'id_kantor' => 1],
            ['id' => 10, 'nama_unit' => 'Bidang Pengembangan & Supervisi Kepegawaian', 'parent' => 1, 'id_kantor' => 1],
            ['id' => 11, 'nama_unit' => 'Seksi Fasilitasi Pengembangan Kepegawaian', 'parent' => 10, 'id_kantor' => 1],
            ['id' => 12, 'nama_unit' => 'Seksi Fasilitas Kinerja', 'parent' => 10, 'id_kantor' => 1],
            ['id' => 13, 'nama_unit' => 'Seksi Supervisi Kepegawaian', 'parent' => 10, 'id_kantor' => 1],
            ['id' => 14, 'nama_unit' => 'Seksi Pengelolaan Arsip Kepegawaian dan Instansi Vertikal dan Provinsi', 'parent' => 5, 'id_kantor' => 1],
            ['id' => 15, 'nama_unit' => 'Seksi Pengelolaan Arsip Kepegawaian dan Instansi Kabupaten / Kota', 'parent' => 5, 'id_kantor' => 1],
            ['id' => 16, 'nama_unit' => 'Seksi Verifikasi dan Pelaporan Pengangkatan dan Pensiun', 'parent' => 9, 'id_kantor' => 1],
            ['id' => 17, 'nama_unit' => 'Seksi Pensiun Pegawai Negeri Sipil Instansi Vertikal dan Provinsi', 'parent' => 9, 'id_kantor' => 1],
            ['id' => 18, 'nama_unit' => 'Seksi Pensun Pegawai Negeri Sipil Instansi Kabupaten / Kota', 'parent' => 9, 'id_kantor' => 1],
            ['id' => 19, 'nama_unit' => 'Seksi Pengangkatan Aparatur Sipil Negara', 'parent' => 9, 'id_kantor' => 1],
            ['id' => 20, 'nama_unit' => 'Seksi Verifikasi dan Pelaporan Mutasi dan Status Kepegawaian', 'parent' => 8, 'id_kantor' => 1],
            ['id' => 21, 'nama_unit' => 'Seksi Mutasi Instansi Vertikal dan Provinsi', 'parent' => 8, 'id_kantor' => 1],
            ['id' => 22, 'nama_unit' => 'Seksi Mutasi Instansi Kabupaten / Kota', 'parent' => 8, 'id_kantor' => 1],
            ['id' => 23, 'nama_unit' => 'Seksi Status Kepegawaian', 'parent' => 8, 'id_kantor' => 1],
            ['id' => 24, 'nama_unit' => 'Sub Bagian Perencanaan dan Keuangan', 'parent' => 2, 'id_kantor' => 1],
        ];
        DB::table('units')->insert($units);
    }
}
