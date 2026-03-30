<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input + Captcha
        $validated = $request->validate([
            'nip' => 'required|string',
            'nama' => 'required|string',
            'jabatan' => 'nullable|string',
            'instansi' => 'nullable|string',
            'topik' => 'required', // UBAH: Hilangkan |integer dari sini
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'sesi' => 'required|string',
            'email' => 'required|email',
            'hp' => 'required|string',
            'captchaInput' => 'required|captcha'
        ], [
            'captchaInput.captcha' => 'Kode Keamanan (Captcha) yang Anda masukkan salah atau kadaluarsa.'
        ]);

        // 2. Simpan ke Database
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

        // Note: Zoom akan kita integrasikan di Fase 4.
        // Sementara kita return success.

        return response()->json([
            'success' => true,
            'message' => 'Permintaan konsultasi berhasil dikirim!',
            'data' => $consultation
        ]);
    }
}
