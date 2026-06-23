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

        $jamMulai = $this->getStartTimeFromSesi($validated['sesi']);
        $zoomStartTime = $validated['tanggal'] . 'T' . $jamMulai;

        $topik = Topik::find($validated['topik']);
        $judulZoom = 'Konsultasi BKN: ' . ($topik ? $topik->nama_topik : $validated['nama']);

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

        try {
            Mail::to($consultation->email)->send(new ConsultationMail($consultation));
        } catch (\Exception $e) {
            Log::error('EMAIL ERROR: ' . $e->getMessage());
        }

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
        $consultations = Consultation::with('petugas')->get();

        $topiks = Topik::pluck('nama_topik', 'id')->toArray();

        $labels = [
            'sesi-1' => 'Sesi 1 (09:00 - 09:45)',
            'sesi-2' => 'Sesi 2 (10:00 - 10:45)',
            'sesi-3' => 'Sesi 3 (11:00 - 11:45)',
            'sesi-4' => 'Sesi 4 (14:00 - 14:45)',
            'sesi-5' => 'Sesi 5 (15:00 - 15:45)',
        ];

        $formatted = $consultations->groupBy('tanggal')->map(function ($items, $date) use ($labels, $topiks) {
            return [
                'tanggal' => $date,
                'sesi' => $items->map(function ($item) use ($labels, $topiks) {
                    return [
                        'value' => $item->sesi,
                        'label' => $labels[$item->sesi] ?? $item->sesi,
                        'narasumber' => $item->petugas->name ?? 'Menunggu Plotting',
                        'topik' => $topiks[$item->topik_id] ?? 'Konsultasi Kepegawaian',
                        'link' => $item->zoom_link,
                        'passcode' => $item->zoom_passcode,
                    ];
                })->values()
            ];
        })->values();

        return response()->json($formatted);
    }

    public function dashboardAdmin(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfWeek()->toDateString());
        $endDate = $request->query('end_date', now()->endOfWeek()->toDateString());

        $query = Consultation::whereBetween('tanggal', [$startDate, $endDate]);

        $stats = [
            'total' => (clone $query)->count(),
            'aktif' => (clone $query)->where('status', 'akan_datang')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        $topik = (clone $query)->selectRaw('topik_id, count(*) as jumlah')
            ->with('topik')
            ->groupBy('topik_id')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->topik->nama_topik ?? 'Konsultasi Kepegawaian',
                    'jumlah' => $item->jumlah
                ];
            });

        $instansi = (clone $query)->selectRaw('instansi as nama, count(*) as jumlah')
            ->whereNotNull('instansi')
            ->groupBy('instansi')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $kinerja = (clone $query)
            ->join('users', 'consultations.petugas_id', '=', 'users.id')
            ->selectRaw('users.name as nama, count(consultations.id) as jumlah')
            ->whereNotNull('consultations.petugas_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('jumlah')
            ->get();

        return inertia('Dashboard', [
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'stats' => $stats,
            'topik' => $topik,
            'instansi' => $instansi,
            'kinerja' => $kinerja,
        ]);
    }

    public function indexAdmin()
    {
        $klien = \App\Models\Consultation::with(['topik', 'petugas'])->orderBy('created_at', 'desc')->get();
        $formattedData = \App\Http\Resources\ConsultationResource::collection($klien);

        $narasumberList = \App\Models\User::where('level_id', 4)
            ->select('id', 'name as nama')
            ->orderBy('name', 'asc')
            ->get();

        return inertia('Klien/Index', [
            'klienData' => $formattedData,
            'narasumberList' => $narasumberList
        ]);
    }

    public function assignNarasumber(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'narasumber' => 'required'
        ]);

        $consultation = \App\Models\Consultation::findOrFail($id);
        $consultation->update([
            'petugas_id' => $request->narasumber
        ]);

        return redirect()->back()->with('success', 'Narasumber berhasil ditugaskan.');
    }
}
