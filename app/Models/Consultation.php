<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'nip', 'nama', 'jabatan', 'instansi', 'email', 'no_hp',
        'topik_id', 'deskripsi_masalah', 'tanggal', 'sesi',
        'status', 'petugas_id', 'zoom_meeting_id', 'zoom_link', 'zoom_passcode'
    ];
}
