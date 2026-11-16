<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $fillable = [
        'nama_workshop',
        'deskripsi',
        'pemateri',
        'tanggal',
        'waktu',
        'lokasi',
        'harga',
        'kuota',
        'status',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
