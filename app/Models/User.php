<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['email', 'password', 'role', 'nama_lengkap', 'no_telepon'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /** Tabel users tidak punya kolom remember_token. */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function pasien(): HasOne
    {
        return $this->hasOne(Pasien::class, 'user_id');
    }

    public function terapis(): HasOne
    {
        return $this->hasOne(Terapis::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }

    public function isTerapis(): bool
    {
        return $this->role === 'terapis';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin' => route('admin.dashboard'),
            'terapis' => route('terapis.jadwal.index'),
            default => route('pasien.dashboard'),
        };
    }
}
