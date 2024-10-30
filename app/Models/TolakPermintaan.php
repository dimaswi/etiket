<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TolakPermintaan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kotak_saran_id',
        'tiket_id',
        'worker',
        'alasan',
    ];
}
