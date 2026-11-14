@extends('layouts.auth')

@section('title', 'Daftar')
@section('card_class', 'wide')

@section('content')
    <p class="login-subtitle">Buat akun pasien Life Balance</p>

    @if ($errors->any())
        <div class="alert-custom alert-custom-danger mb-3">
            <i class="bi bi-exclamation-triangle-fill alert-custom-icon"></i>
            <div class="alert-custom-content"><ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="login-form-label" for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control-custom" required>
            </div>
            <div class="col-md-6">
                <label class="login-form-label" for="no_telepon">No. Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" class="form-control-custom" required>
            </div>
            <div class="col-md-6">
                <label class="login-form-label" for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control-custom" required>
            </div>
            <div class="col-md-3">
                <label class="login-form-label" for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ now()->subDay()->toDateString() }}" class="form-control-custom" required>
            </div>
            <div class="col-md-3">
                <label class="login-form-label" for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="form-select-custom" required>
                    <option value="">Pilih</option>
                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="col-12">
                <label class="login-form-label" for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" class="form-control-custom" required>{{ old('alamat') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="login-form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control-custom" minlength="8" required>
            </div>
            <div class="col-md-6">
                <label class="login-form-label" for="password_confirmation">Ulangi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" minlength="8" required>
            </div>
        </div>

        <button type="submit" class="btn-login mt-4">
            <span>Daftar</span><i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <p class="login-footer-text">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
