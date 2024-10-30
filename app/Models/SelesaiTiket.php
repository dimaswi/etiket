<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelesaiTiket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kotak_saran_id',
        'tiket_id',
        'worker',
        'pesan',
        'lampiran',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker', 'id');
    }
}
