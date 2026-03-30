<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultation;
use Carbon\Carbon;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        $instansiList = [
            'BKD Prov. Sulawesi Utara', 'BKPSDM Kota Manado',
            'BKPSDM Kab. Minahasa', 'BKPSDM Kota Tomohon', 'BKPSDM Kota Bitung'
        ];
        $narasumberList = ['Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Rina Melati'];
        $sesiList = ['sesi-1', 'sesi-2', 'sesi-3', 'sesi-4', 'sesi-5'];

        // Acuan waktu saat ini (30 Maret 2026)
        $currentDate = Carbon::create(2026, 3, 30, 22, 6, 0, 'Asia/Makassar');

        // Generate 40 data konsultasi acak
        for ($i = 1; $i <= 40; $i++) {
            // Sebar tanggal dari 14 hari yang lalu hingga 14 hari ke depan
            $daysOffset = rand(-14, 14);
            $tanggal = (clone $currentDate)->addDays($daysOffset)->format('Y-m-d');

            // Logika Status: Karena sudah jam 22:06, hari ini dan sebelumnya = Selesai
            $isPast = $daysOffset <= 0;
            $status = $isPast ? 'selesai' : 'akan_datang';

            // Logika Narasumber: Jika sudah selesai, PASTI ada narasumber.
            // Jika akan datang, 50% kemungkinan belum di-plot (null).
            $narasumber = null;
            if ($isPast || rand(0, 1)) {
                $narasumber = $narasumberList[array_rand($narasumberList)];
            }

            Consultation::create([
                'nip' => '19' . rand(70, 99) . rand(10, 12) . rand(10, 30) . '20' . rand(10, 24) . '100' . rand(1, 9),
                'nama' => 'ASN Klien ' . $i,
                'jabatan' => 'Analis Sumber Daya Manusia Aparatur',
                'instansi' => $instansiList[array_rand($instansiList)],
                'topik_id' => rand(1, 17), // Asumsi Anda punya 17 topik di database
                'deskripsi_masalah' => 'Ini adalah data simulasi deskripsi masalah kepegawaian untuk testing sistem e-con BKN fase 5.',
                'tanggal' => $tanggal,
                'sesi' => $sesiList[array_rand($sesiList)],
                'email' => 'klien' . $i . '@example.com',
                'no_hp' => '0812' . rand(10000000, 99999999),
                'status' => $status,
                'narasumber' => $narasumber,
                'zoom_meeting_id' => '8' . rand(1000000000, 9999999999),
                'zoom_link' => 'https://zoom.us/j/dummy' . $i,
                'zoom_passcode' => 'econ11',
            ]);
        }
    }
}
