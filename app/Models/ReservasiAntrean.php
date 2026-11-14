<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class ReservasiAntrean extends Model
{
    public const STATUS = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'terjadwal' => 'Terjadwal',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
        'batal' => 'Batal',
    ];

    protected $table = 'reservasi_antrean';

    protected $fillable = [
        'kode_booking', 'pasien_id', 'paket_layanan_id', 'terapis_id',
        'tanggal_praktik', 'jam_mulai', 'jam_selesai', 'nomor_antrean', 'status_antrean',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_praktik' => 'date',
            'nomor_antrean' => 'integer',
        ];
    }

    public static function generateKode(): string
    {
        do {
            $kode = 'LB-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (static::where('kode_booking', $kode)->exists());

        return $kode;
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_layanan_id');
    }

    public function terapis(): BelongsTo
    {
        return $this->belongsTo(Terapis::class, 'terapis_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'reservasi_id')->latestOfMany();
    }

    public function scopeAktif($query)
    {
        return $query->where('status_antrean', '!=', 'batal');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status_antrean] ?? $this->status_antrean;
    }

    public function getJamAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5).' - '.substr($this->jam_selesai, 0, 5);
    }
}
