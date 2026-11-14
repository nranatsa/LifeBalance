@extends('layouts.hopewell')

@section('title', 'Profil Saya')

@section('content')
<section class="page-banner">
    <div class="container">
        <h1 class="section-title" style="font-size:clamp(1.8rem,3vw,2.3rem);">Profil Saya</h1>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <form action="{{ route('pasien.profil.update') }}" method="POST" enctype="multipart/form-data" class="hw-panel">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <img src="{{ $pasien->foto_profil ? asset('storage/'.$pasien->foto_profil) : asset('hopewell/assets/hand-heart.png') }}" class="rounded-circle border" style="width:72px;height:72px;object-fit:cover" alt="">
                    <div>
                        <label class="form-label fw-semibold mb-1">Foto Profil</label>
                        <input type="file" name="foto_profil" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">No. Telepon</label><input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir->toDateString()) }}" required></div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label fw-semibold">Alamat</label><textarea name="alamat" rows="2" class="form-control" required>{{ old('alamat', $pasien->alamat) }}</textarea></div>
                <div class="col-12"><label class="form-label fw-semibold">Catatan Medis / Keluhan</label><textarea name="catatan_medis" rows="3" class="form-control">{{ old('catatan_medis', $pasien->catatan_medis) }}</textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Password Baru <small class="text-muted">(opsional)</small></label><input type="password" name="password" class="form-control" minlength="8"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Ulangi Password Baru</label><input type="password" name="password_confirmation" class="form-control" minlength="8"></div>
            </div>
            <button type="submit" class="btn-hw-primary mt-4">Simpan Perubahan <i class="bi bi-check-lg"></i></button>
        </form>
    </div>
</section>
@endsection
