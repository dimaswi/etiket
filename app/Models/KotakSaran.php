<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KotakSaran extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama',
        'nomor',
        'email',
        'pesan',
        'status',
        'lampiran'
    ];

    public function tolak(): HasMany
    {
        return $this->hasMany(TolakTiket::class, 'kotak_saran_id', 'id');
    }

    public function selesai(): HasMany
    {
        return $this->hasMany(SelesaiTiket::class, 'kotak_saran_id', 'id');
    }
}
