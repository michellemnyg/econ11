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
        $sesiList = ['sesi-1', 'sesi-2', 'sesi-3', 'sesi-4', 'sesi-5'];

        $narasumberUser = \App\Models\User::where('username', 'narasumber')->first();
        $narasumberId = $narasumberUser ? $narasumberUser->id : null;

        $now = Carbon::now('Asia/Makassar')->startOfDay();

        // Helper untuk mencari hari valid (bukan akhir pekan)
        $getValidFutureDay = function ($daysToAdd, $forceTuesday = false) use ($now) {
            $date = (clone $now)->addDays($daysToAdd);
            while ($date->isWeekend() || ($forceTuesday && $date->dayOfWeek !== Carbon::TUESDAY) || (!$forceTuesday && $date->dayOfWeek === Carbon::TUESDAY)) {
                $date->addDay();
            }
            return $date;
        };

        $getValidPastDay = function ($daysToSub) use ($now) {
            $date = (clone $now)->subDays($daysToSub);
            while ($date->isWeekend()) {
                $date->subDay();
            }
            return $date;
        };

        // Kumpulan Skenario Hari
        $scenarios = [
            // 1. Hari Berlalu (Past) - 2 Sesi
            [
                'tanggal' => $getValidPastDay(3),
                'sesi_count' => 2,
            ],
            // 2. Hari Berlalu (Past) - 1 Sesi
            [
                'tanggal' => $getValidPastDay(1),
                'sesi_count' => 1,
            ],
            // 3. Hari Masa Depan (Future - Non-Selasa) - PENUH (5 Sesi)
            [
                'tanggal' => $getValidFutureDay(1, false),
                'sesi_count' => 5,
            ],
            // 4. Hari Masa Depan (Future - Selasa) - PENUH KHUSUS SELASA (4 Sesi)
            [
                'tanggal' => $getValidFutureDay(2, true),
                'sesi_count' => 4,
            ],
            // 5. Hari Masa Depan Jauh (Future) - 1 Sesi (Belum di Assign)
            [
                'tanggal' => $getValidFutureDay(7, false),
                'sesi_count' => 1,
                'no_assign' => true
            ]
        ];

        $i = 1;
        foreach ($scenarios as $scenario) {
            $tanggal = $scenario['tanggal'];
            
            // Aturan Selasa Sesi 1 ditiadakan
            $availableSesi = $sesiList;
            if ($tanggal->dayOfWeek === Carbon::TUESDAY) {
                $availableSesi = array_values(array_diff($sesiList, ['sesi-1']));
            }

            for ($j = 0; $j < $scenario['sesi_count']; $j++) {
                // Mencegah error jika sesi count melebihi slot yang tersedia
                if (!isset($availableSesi[$j])) break; 

                $isUnassigned = isset($scenario['no_assign']) && $scenario['no_assign'];

                Consultation::create([
                    'nip' => '19900101202012100' . rand(1, 9),
                    'nama' => 'PNS Simulasi ' . $i,
                    'jabatan' => 'Analis Sumber Daya Manusia Aparatur',
                    'instansi' => $instansiList[array_rand($instansiList)],
                    'topik_id' => rand(1, 17),
                    'deskripsi_masalah' => 'Ini adalah data uji coba skenario ke-' . $i,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'sesi' => $availableSesi[$j],
                    'email' => 'klien' . $i . '@example.com',
                    'no_hp' => '0812' . rand(10000000, 99999999),
                    'petugas_id' => $isUnassigned ? null : $narasumberId,
                    'zoom_meeting_id' => $isUnassigned ? null : '8' . rand(1000000000, 9999999999),
                    'zoom_link' => $isUnassigned ? null : 'https://zoom.us/j/dummy' . $i,
                    'zoom_passcode' => $isUnassigned ? null : 'econ11',
                ]);
                $i++;
            }
        }
    }
}
