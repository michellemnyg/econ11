<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    // Pastikan semua kolom yang ada di database diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'nip', 'nama', 'jabatan', 'instansi', 'topik_id',
        'deskripsi_masalah', 'tanggal', 'sesi', 'email', 'no_hp',
        'status', 'narasumber', 'zoom_meeting_id', 'zoom_link', 'zoom_passcode'
    ];

    /**
     * Relasi ke tabel Topik
     */
    public function topik()
    {
        // belongsTo karena tabel consultations memiliki foreign key topik_id
        return $this->belongsTo(Topik::class, 'topik_id');
    }
}
