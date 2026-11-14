<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketLayanan extends Model
{
    protected $table = 'paket_layanan';

    protected $fillable = [
        'nama_paket', 'foto_paket', 'deskripsi', 'durasi_menit', 'harga', 'status_publish',
    ];

    protected function casts(): array
    {
        return [
            'durasi_menit' => 'integer',
            'harga' => 'decimal:2',
            'status_publish' => 'boolean',
        ];
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(ReservasiAntrean::class, 'paket_layanan_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status_publish', true);
    }
}
