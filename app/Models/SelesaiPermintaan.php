<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelesaiPermintaan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'permintaan_id',
        'tiket_unit_id',
        'worker',
        'pesan',
        'lampiran',
    ];
}
