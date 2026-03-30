<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Topik;
use App\Services\ZoomService;
use App\Mail\ConsultationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    public function store(Request $request, ZoomService $zoomService)
    {
        // 1. Validasi Input + Captcha
        $validated = $request->validate([
            'nip' => 'required|string',
            'nama' => 'required|string',
            'jabatan' => 'nullable|string',
            'instansi' => 'nullable|string',
            'topik' => 'required',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'sesi' => 'required|string',
            'email' => 'required|email',
            'hp' => 'required|string',
            'captchaInput' => 'required|captcha'
        ], [
            'captchaInput.captcha' => 'Kode Keamanan (Captcha) yang Anda masukkan salah atau kadaluarsa.'
        ]);

        // 2. Simpan Data Awal ke Database
        $consultation = Consultation::create([
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'instansi' => $validated['instansi'],
            'topik_id' => $validated['topik'],
            'deskripsi_masalah' => $validated['deskripsi'],
            'tanggal' => $validated['tanggal'],
            'sesi' => $validated['sesi'],
            'email' => $validated['email'],
            'no_hp' => $validated['hp'],
            'status' => 'akan_datang',
        ]);

        // 3. Konversi Sesi ke Jam untuk Zoom
        $jamMulai = $this->getStartTimeFromSesi($validated['sesi']);
        $zoomStartTime = $validated['tanggal'] . 'T' . $jamMulai;

        $topik = Topik::find($validated['topik']);
        $judulZoom = 'Konsultasi BKN: ' . ($topik ? $topik->nama_topik : $validated['nama']);

        // 4. Generate Zoom Meeting
        try {
            $zoomData = $zoomService->createMeeting($judulZoom, $zoomStartTime);

            $consultation->update([
                'zoom_meeting_id' => $zoomData['id'],
                'zoom_link' => $zoomData['join_url'],
                'zoom_passcode' => $zoomData['password'],
            ]);

            $consultation->refresh();

        } catch (\Exception $e) {
            Log::error('ZOOM API ERROR: ' . $e->getMessage());
        }

        // 5. KIRIM EMAIL (Queue otomatis karena kita pakai ShouldQueue di Mailable)
        try {
            Mail::to($consultation->email)->send(new ConsultationMail($consultation));
        } catch (\Exception $e) {
            Log::error('EMAIL ERROR: ' . $e->getMessage());
        }

        // 6. Response ke Frontend
        return response()->json([
            'success' => true,
            'message' => 'Permintaan konsultasi berhasil dikirim!',
            'data' => $consultation,
            'meeting' => [
                'platform' => 'Zoom Meeting',
                'link' => $consultation->zoom_link ?? 'Akan dikirim via email.',
                'meetingId' => $consultation->zoom_meeting_id ?? '-',
                'passcode' => $consultation->zoom_passcode ?? 'econ11',
                'tanggal' => $validated['tanggal'],
                'sesi' => $validated['sesi'],
            ]
        ]);
    }

    private function getStartTimeFromSesi($sesiKode)
    {
        $jadwal = [
            'sesi-1' => '09:00:00',
            'sesi-2' => '10:00:00',
            'sesi-3' => '11:00:00',
            'sesi-4' => '14:00:00',
            'sesi-5' => '15:00:00',
        ];
        return $jadwal[$sesiKode] ?? '09:00:00';
    }

    /**
     * Endpoint untuk cek ketersediaan sesi (digunakan useConsultationSchedule.js)
     */
    public function getBookedSessions(Request $request)
    {
        $date = $request->query('date');
        $booked = Consultation::where('tanggal', $date)
            ->select('sesi as session')
            ->get();

        return response()->json($booked);
    }

    /**
     * Mengambil semua data konsultasi untuk kalender hero page
     */
    public function getCalendarSessions()
    {
        // 1. Ambil SEMUA data tanpa filter status, agar semua muncul di kalender.
        $consultations = Consultation::all();

        // 2. Ambil semua topik sebagai array dictionary untuk mencegah Error 500 (Relasi)
        $topiks = Topik::pluck('nama_topik', 'id')->toArray();

        // 3. Siapkan label sesi
        $labels = [
            'sesi-1' => 'Sesi 1 (09:00 - 09:45)',
            'sesi-2' => 'Sesi 2 (10:00 - 10:45)',
            'sesi-3' => 'Sesi 3 (11:00 - 11:45)',
            'sesi-4' => 'Sesi 4 (14:00 - 14:45)',
            'sesi-5' => 'Sesi 5 (15:00 - 15:45)',
        ];

        // 4. Kelompokkan berdasarkan tanggal dan format untuk Vue
        $formatted = $consultations->groupBy('tanggal')->map(function ($items, $date) use ($labels, $topiks) {
            return [
                'tanggal' => $date,
                'sesi' => $items->map(function ($item) use ($labels, $topiks) {
                    return [
                        'value' => $item->sesi,
                        'label' => $labels[$item->sesi] ?? $item->sesi,
                        'narasumber' => $item->narasumber ?? 'Menunggu Plotting',
                        // Jika topik tidak ditemukan, tampilkan teks default
                        'topik' => $topiks[$item->topik_id] ?? 'Konsultasi Kepegawaian',
                        'link' => $item->zoom_link,
                        'passcode' => $item->zoom_passcode,
                    ];
                })->values()
            ];
        })->values();

        return response()->json($formatted);
    }
}
