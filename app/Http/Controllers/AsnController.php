<?php

namespace App\Http\Controllers;

use App\Models\Pns;
use Illuminate\Http\Request;

class AsnController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'nama' => 'required|string',
        ]);

        // Cari berdasarkan NIP dan kemiripan Nama (Case Insensitive)
        $pns = Pns::where('nip_baru', $request->nip)
                  ->where('nama', 'LIKE', '%' . $request->nama . '%')
                  ->first();

        if ($pns) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $pns->nama,
                    // Fallback jika nama_jabatan kosong, ambil struktural
                    'jabatan' => $pns->nama_jabatan ?? $pns->struktural_nama_jabatan ?? 'ASN',
                    // Fallback nama instansi
                    'instansi' => $pns->unor_induk_nama ?? 'Instansi Tidak Diketahui',
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan. Pastikan NIP dan Nama sesuai.'
        ], 404);
    }
}
