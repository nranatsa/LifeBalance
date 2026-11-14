<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'reservasi_id', 'total_bayar', 'metode_pembayaran', 'bukti_pembayaran',
        'status_pembayaran', 'diverifikasi_oleh', 'tanggal_bayar',
    ];

    protected function casts(): array
    {
        return [
            'total_bayar' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(ReservasiAntrean::class, 'reservasi_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
