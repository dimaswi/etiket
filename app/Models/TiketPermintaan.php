<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketPermintaan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'permintaan_id',
        'worker',
        'pemberi',
        'masukan',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    public function pemberi_permintaan()
    {
        return $this->belongsTo(User::class, 'pemberi');
    }
}
