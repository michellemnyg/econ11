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

        $schedules = [];
        $counts = [2, 5, 1];
        $currentDate = Carbon::now()->addDay();
        
        foreach ($counts as $count) {
            while ($currentDate->isWeekend()) {
                $currentDate->addDay();
            }
            $schedules[] = ['tanggal' => clone $currentDate, 'count' => $count];
            $currentDate->addDay();
        }

        $i = 1;
        foreach ($schedules as $schedule) {
            $availableSesi = $sesiList;
            if ($schedule['tanggal']->dayOfWeek === Carbon::TUESDAY) {
                $availableSesi = array_values(array_diff($sesiList, ['sesi-1']));
            }

            for ($j = 0; $j < $schedule['count']; $j++) {
                Consultation::create([
                    'nip' => 'nip',
                    'nama' => 'nama',
                    'jabatan' => 'Analis Sumber Daya Manusia Aparatur',
                    'instansi' => $instansiList[array_rand($instansiList)],
                    'topik_id' => rand(1, 17),
                    'deskripsi_masalah' => 'Ini adalah data simulasi terjadwal ke-' . $i,
                    'tanggal' => $schedule['tanggal']->format('Y-m-d'),
                    'sesi' => $availableSesi[$j % count($availableSesi)],
                    'email' => 'klien' . $i . '@example.com',
                    'no_hp' => '0812' . rand(10000000, 99999999),
                    'petugas_id' => $narasumberId,
                    'zoom_meeting_id' => '8' . rand(1000000000, 9999999999),
                    'zoom_link' => 'https://zoom.us/j/dummy' . $i,
                    'zoom_passcode' => 'econ11',
                ]);
                $i++;
            }
        }

        $randomBaseDate = Carbon::now()->addDays(5);
        for ($k = 1; $k <= 10; $k++) {
            while ($randomBaseDate->isWeekend()) {
                $randomBaseDate->addDay();
            }

            $availSesi = $sesiList;
            if ($randomBaseDate->dayOfWeek === Carbon::TUESDAY) {
                $availSesi = array_values(array_diff($sesiList, ['sesi-1']));
            }

            Consultation::create([
                'nip' => 'nip',
                'nama' => 'nama',
                'jabatan' => 'Jabatan Acak',
                'instansi' => $instansiList[array_rand($instansiList)],
                'topik_id' => rand(1, 17),
                'deskripsi_masalah' => 'Konsultasi acak belum di-assign ke-' . $k,
                'tanggal' => (clone $randomBaseDate)->format('Y-m-d'),
                'sesi' => $availSesi[array_rand($availSesi)],
                'email' => 'random' . $k . '@example.com',
                'no_hp' => '0812' . rand(10000000, 99999999),
                'petugas_id' => null,
                'zoom_meeting_id' => null,
                'zoom_link' => null,
                'zoom_passcode' => null,
            ]);
            $randomBaseDate->addDays(rand(1, 3));
        }
    }
}
