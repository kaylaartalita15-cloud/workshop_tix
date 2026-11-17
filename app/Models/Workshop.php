<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $fillable = [
        'nama_workshop',
        'kategori',
        'deskripsi',
        'pemateri',
        'tanggal',
        'waktu',
        'lokasi',
        'harga',
        'kuota',
        'gambar',
        'status',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Hitung sisa tiket yang tersedia secara akurat
     * Tiket berkurang bila pendaftaran berstatus lunas atau menunggu pembayaran
     */
    public function getSisaTiketAttribute(): int
    {
        $terpakai = $this->registrations()->whereIn('status', ['lunas', 'menunggu_pembayaran', 'menunggu'])->count();
        $sisa = $this->kuota - $terpakai;
        return max(0, $sisa);
    }

    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar && str_starts_with($this->gambar, 'http')) {
            return $this->gambar;
        } elseif ($this->gambar && file_exists(public_path('storage/' . $this->gambar))) {
            return asset('storage/' . $this->gambar);
        }
        
        // Default gambar ilustrasi IT tech workshop berkualitas tinggi dari Unsplash
        $defaults = [
            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&auto=format&fit=crop&q=80',
        ];

        return $defaults[$this->id % count($defaults)] ?? $defaults[0];
    }
}
