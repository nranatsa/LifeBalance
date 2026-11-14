{{-- Layout template HOPEWELL: dipakai oleh role PASIEN --}}
<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.hopewell.head')
</head>
<body>

    <div id="preloader" aria-hidden="true">
        <div class="preload-ring"><i class="bi bi-heart-fill preload-heart"></i></div>
        <div class="preload-text">MEMUAT&hellip;</div>
    </div>

    @include('partials.hopewell.navbar')

    <main class="@yield('main_class', 'pt-5 mt-4')">
        <div class="@yield('alerts_class', 'container pt-4')">
            @include('partials.hopewell.alerts')
        </div>

        @yield('content')
    </main>

    @include('partials.hopewell.footer')

    <div id="scrollTop" role="button" aria-label="Kembali ke atas" tabindex="0">
        <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="23"></circle><circle class="progress" cx="26" cy="26" r="23"></circle></svg>
        <i class="bi bi-arrow-up"></i>
    </div>

    @include('partials.hopewell.scripts')
</body>
</html>
