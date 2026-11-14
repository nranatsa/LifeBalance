{{-- Layout halaman login & registrasi (memakai gaya halaman login template SPARK) --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('spark/assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('spark/assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('spark/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('spark/assets/css/main.css') }}">
    <style>.login-card.wide{max-width:640px}</style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <div class="login-card @yield('card_class')">
            <a href="{{ url('/') }}" class="login-brand text-decoration-none">
                <i class="bi bi-asterisk"></i>
                <span>Life Balance</span>
            </a>

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('spark/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('spark/assets/js/auth.js') }}"></script>
</body>
</html>
