<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ConsultationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Paksa jadi YYYY-MM-DD agar tidak menjadi string ISO panjang yang merusak JavaScript
            'tanggal' => Carbon::parse($this->tanggal)->format('Y-m-d'),
            'sesi' => $this->sesi,
            'nip' => $this->nip,
            'nama' => $this->nama,
            'instansi' => $this->instansi ?? '-',
            'topik' => $this->topik->nama_topik ?? '-',
            'status' => $this->status,
            'narasumber' => $this->petugas ? $this->petugas->name : null,
            'petugas' => $this->petugas ? $this->petugas->name : null,
            'deskripsiMasalah' => $this->deskripsi_masalah,
            'integrasi' => [
                'linkZoom' => $this->zoom_link,
                'meetingId' => $this->zoom_meeting_id,
                'passcode' => $this->zoom_passcode,
                'createdAt' => $this->created_at ? $this->created_at->toISOString() : null,
            ],
            'createdAt' => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}
