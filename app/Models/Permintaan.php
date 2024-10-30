<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permintaan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['pemohon', 'subjek', 'pesan', 'lampiran', 'status'];

    public function peminta()
    {
        return $this->belongsTo(User::class, 'pemohon');
    }

    public function tolak(): HasMany
    {
        return $this->hasMany(TolakPermintaan::class, 'permintaan_id', 'id');
    }

    public function selesai(): HasMany
    {
        return $this->hasMany(SelesaiPermintaan::class, 'permintaan_id', 'id');
    }
}
