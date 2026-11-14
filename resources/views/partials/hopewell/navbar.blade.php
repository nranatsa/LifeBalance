@auth
<nav class="navbar navbar-expand-lg navbar-hw" id="mainNav">
    <div class="container">
        <a class="hw-logo" href="{{ route('pasien.dashboard') }}">
            <span class="hw-logo-mark"><i class="bi bi-heart-fill"></i></span>
            Life<span class="dot">Balance</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Buka menu">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav nav-hw mx-auto mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}" href="{{ route('pasien.dashboard') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pasien.reservasi.*') ? 'active' : '' }}" href="{{ route('pasien.reservasi.index') }}">Reservasi Saya</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pasien.pembayaran.*') ? 'active' : '' }}" href="{{ route('pasien.pembayaran.index') }}">Pembayaran</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pasien.profil.*') ? 'active' : '' }}" href="{{ route('pasien.profil.edit') }}">Profil</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <a href="{{ route('pasien.reservasi.create') }}" class="btn-hw-primary">Buat Reservasi <i class="bi bi-arrow-right"></i></a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-hw-outline" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div>
</nav>
@else
<nav class="navbar navbar-expand-lg navbar-hw" id="mainNav">
    <div class="container">
        <a class="hw-logo" href="{{ url('/') }}#home">
            <span class="hw-logo-mark"><i class="bi bi-heart-fill"></i></span>
            Life<span class="dot">Balance</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Buka menu">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav nav-hw mx-auto mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#cara-kerja">Cara Kerja</a></li>
                <li class="nav-item"><a class="nav-link" href="#terapis">Terapis</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <a href="{{ route('login') }}" class="btn-hw-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-hw-primary">Daftar <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</nav>
@endauth
