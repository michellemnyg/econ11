<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pns extends Model
{
    protected $table = 'pns';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = []; // Karena ini tabel master dari luar, kita set guarded kosong
}
