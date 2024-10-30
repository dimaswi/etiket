<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kotak_sarans_id',
        'masukan',
        'worker',
        'status',
        'jam_mulai',
        'jam_selesai',
        'tolak',
    ];

    public function kotakSaran()
    {
        return $this->belongsTo(KotakSaran::class, 'kotak_sarans_id');
    }
}
