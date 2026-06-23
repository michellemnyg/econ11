<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip', 'nama', 'jabatan', 'instansi', 'topik_id',
        'deskripsi_masalah', 'tanggal', 'sesi', 'email', 'no_hp',
        'petugas_id', 'zoom_meeting_id', 'zoom_link', 'zoom_passcode'
    ];

    public function topik()
    {
        return $this->belongsTo(Topik::class, 'topik_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
