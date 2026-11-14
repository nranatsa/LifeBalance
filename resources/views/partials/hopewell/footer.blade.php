<footer class="footer-hw mt-5">
    <svg class="footer-wave" viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path fill="#0B1220" d="M0,32 C240,70 480,0 720,18 C960,36 1200,70 1440,26 L1440,70 L0,70 Z"></path>
    </svg>
    <span class="footer-glow"></span>
    <div class="container footer-top">
        <div class="row g-4">
            <div class="col-lg-6">
                <a class="hw-logo text-white" href="{{ auth()->check() ? route('pasien.dashboard') : url('/') }}">
                    <span class="hw-logo-mark"><i class="bi bi-heart-fill"></i></span>
                    Life<span class="dot" style="color:var(--clr-primary);">Balance</span>
                </a>
                <p class="mt-3">Klinik terapi dengan penjadwalan otomatis. Pilih paket layanan, kami yang mengatur jadwal dan terapis Anda.</p>
            </div>
@auth
            <div class="col-6 col-lg-3">
                <h6>Menu</h6>
                <ul>
                    <li><a class="footer-link" href="{{ route('pasien.reservasi.create') }}"><i class="bi bi-caret-right-fill"></i>Buat Reservasi</a></li>
                    <li><a class="footer-link" href="{{ route('pasien.reservasi.index') }}"><i class="bi bi-caret-right-fill"></i>Reservasi Saya</a></li>
                    <li><a class="footer-link" href="{{ route('pasien.pembayaran.index') }}"><i class="bi bi-caret-right-fill"></i>Pembayaran</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Akun</h6>
                <ul>
                    <li><a class="footer-link" href="{{ route('pasien.profil.edit') }}"><i class="bi bi-caret-right-fill"></i>Profil Saya</a></li>
                </ul>
            </div>
@else
            <div class="col-6 col-lg-3">
                <h6>Jelajahi</h6>
                <ul>
                    <li><a class="footer-link" href="{{ url('/') }}#layanan"><i class="bi bi-caret-right-fill"></i>Layanan</a></li>
                    <li><a class="footer-link" href="{{ url('/') }}#cara-kerja"><i class="bi bi-caret-right-fill"></i>Cara Kerja</a></li>
                    <li><a class="footer-link" href="{{ url('/') }}#terapis"><i class="bi bi-caret-right-fill"></i>Terapis</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Akun</h6>
                <ul>
                    <li><a class="footer-link" href="{{ route('login') }}"><i class="bi bi-caret-right-fill"></i>Masuk</a></li>
                    <li><a class="footer-link" href="{{ route('register') }}"><i class="bi bi-caret-right-fill"></i>Daftar Pasien</a></li>
                </ul>
            </div>
@endauth
        </div>
    </div>
    <div class="container text-center py-3 small" style="opacity:.7">
        &copy; {{ date('Y') }} Life Balance. Seluruh hak cipta dilindungi.
    </div>
</footer>
