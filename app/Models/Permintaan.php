<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['pemohon', 'subjek', 'pesan', 'lampiran', 'status'];

    public function peminta()
    {
        return $this->belongsTo(User::class, 'pemohon');
    }
}
