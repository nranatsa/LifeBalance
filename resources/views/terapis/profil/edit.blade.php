@extends('layouts.spark')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Spesialisasi dan status aktif diatur oleh admin.')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form action="{{ route('terapis.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-custom">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control-custom" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Spesialisasi</label>
                <input type="text" class="form-control-custom" value="{{ $terapis->spesialisasi }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Email</label>
                <input type="email" name="email" class="form-control-custom" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">No. Telepon</label>
                <input type="text" name="no_telepon" class="form-control-custom" value="{{ old('no_telepon', $user->no_telepon) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Password Baru <small class="text-muted">(opsional)</small></label>
                <input type="password" name="password" class="form-control-custom" minlength="8">
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control-custom" minlength="8">
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Foto Profil</label>
                <input type="file" name="foto_profil" class="form-control-custom" accept="image/*">
                @if ($terapis->foto_profil)
                    <img src="{{ asset('storage/'.$terapis->foto_profil) }}" class="mt-2 rounded-circle" style="height:64px;width:64px;object-fit:cover" alt="">
                @endif
            </div>
        </div>
        <button type="submit" class="btn-custom btn-custom-primary mt-4"><i class="bi bi-check-lg"></i> Simpan</button>
    </form>
</div>
@endsection
