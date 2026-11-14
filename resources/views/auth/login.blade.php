@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <p class="login-subtitle">Silakan masuk untuk melanjutkan</p>

    @if (session('success'))
        <div class="alert-custom alert-custom-success mb-3"><i class="bi bi-check-circle-fill alert-custom-icon"></i><div class="alert-custom-content">{{ session('success') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="alert-custom alert-custom-danger mb-3"><i class="bi bi-exclamation-triangle-fill alert-custom-icon"></i><div class="alert-custom-content">{{ $errors->first() }}</div></div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST" id="loginForm" class="needs-validation" novalidate>
        @csrf

        <div class="login-form-group">
            <label for="email" class="login-form-label">Email</label>
            <div class="login-input-group">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="login-input" placeholder="nama@email.com" required autofocus>
            </div>
        </div>

        <div class="login-form-group">
            <label for="password" class="login-form-label">Password</label>
            <div class="login-input-group">
                <i class="bi bi-shield-lock input-icon"></i>
                <input type="password" id="password" name="password" class="login-input login-input-password" placeholder="••••••••" required>
                <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login mt-3" id="btn-submit">
            <span>Masuk</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <p class="login-footer-text">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai pasien</a>
    </p>
@endsection
