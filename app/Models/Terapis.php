<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terapis extends Model
{
    protected $table = 'terapis';

    protected $fillable = ['user_id', 'foto_profil', 'spesialisasi', 'status_aktif'];

    protected function casts(): array
    {
        return ['status_aktif' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault(['nama_lengkap' => '(akun dihapus)']);
    }

    public function jadwalPraktik(): HasMany
    {
        return $this->hasMany(JadwalPraktikTerapis::class, 'terapis_id');
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(ReservasiAntrean::class, 'terapis_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
}
