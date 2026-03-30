<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    // Beri tahu Laravel secara eksplisit nama tabelnya
    protected $table = 'topiks';

    // Karena ini tabel referensi statis, kita bisa biarkan guarded kosong
    protected $guarded = [];
}
