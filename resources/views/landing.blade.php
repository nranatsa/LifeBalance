@extends('layouts.hopewell')

@section('title', 'Terapi Terjadwal Otomatis')
@section('main_class', '')
@section('alerts_class', 'container position-relative')

@section('content')

{{-- ============================ HERO ============================ --}}
<header class="hero" id="home">
    <div class="hero-glow"></div>
    <span class="blob blob-1"></span>
    <span class="blob blob-2"></span>
    <span class="blob blob-3"></span>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="eyebrow"><i class="bi bi-stars"></i> Klinik Terapi Life Balance</span>
                <h1 class="hero-title mt-4">Pulih lebih terarah, <span class="accent-underline">jadwal<svg viewBox="0 0 200 14" preserveAspectRatio="none"><path d="M2 10 Q 50 2 100 8 T 198 6" stroke="#F59E0B" stroke-width="5" fill="none" stroke-linecap="round"/></svg></span> diatur otomatis.</h1>
                <p class="hero-lead mt-4">Pilih paket layanan dan tanggal yang Anda inginkan. Sistem langsung menampilkan terapis yang tersedia, tanpa antre telepon dan tanpa tebak-tebakan jam kosong.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('register') }}" class="btn-hw-primary">Daftar &amp; Reservasi <i class="bi bi-arrow-right"></i></a>
                    <a href="#layanan" class="btn-hw-outline"><i class="bi bi-grid"></i> Lihat Layanan</a>
                </div>
                <div class="d-flex align-items-center gap-3 mt-4 text-muted small">
                    <i class="bi bi-calendar-check fs-4" style="color:var(--clr-primary)"></i>
                    <span>Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a></span>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="120">
                <div class="hero-media">
                    <div class="hero-media-frame">
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4"
                             style="background:linear-gradient(145deg,var(--clr-primary-light),#fff 60%,var(--clr-secondary-light));">
                            <i class="bi bi-heart-pulse" style="font-size:6rem;color:var(--clr-primary);"></i>
                            <div class="font-display fw-bold fs-4 mt-3">Terapi yang tertata</div>
                            <div class="text-muted">Dari pilih paket sampai bertemu terapis</div>
                        </div>
                    </div>
                    <div class="float-card float-card-1">
                        <span class="fc-icon"><i class="bi bi-person-heart"></i></span>
                        <div><div class="fc-val">{{ $stat['terapis'] }}</div><div class="fc-label">Terapis aktif</div></div>
                    </div>
                    <div class="float-card float-card-2">
                        <span class="fc-icon"><i class="bi bi-calendar2-check"></i></span>
                        <div><div class="fc-val">Otomatis</div><div class="fc-label">Jadwal &amp; terapis</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ============================ STATISTIK ============================ --}}
<section class="section-pad pt-0" id="statistik">
    <div class="container">
        <div class="stats-band" data-aos="zoom-in">
            <div class="row g-0">
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num"><span class="counter" data-target="{{ $stat['terapis'] }}">0</span></div>
                    <div class="stat-label">Terapis</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num"><span class="counter" data-target="{{ $stat['paket'] }}">0</span></div>
                    <div class="stat-label">Paket Layanan</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num"><span class="counter" data-target="{{ $stat['pasien'] }}">0</span></div>
                    <div class="stat-label">Pasien Terdaftar</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num"><span class="counter" data-target="{{ $stat['sesi_selesai'] }}">0</span></div>
                    <div class="stat-label">Sesi Terselesaikan</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================ PAKET LAYANAN ============================ --}}
<section class="section-pad bg-white" id="layanan">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;" data-aos="fade-up">
            <span class="eyebrow"><i class="bi bi-heart-pulse"></i> Paket Layanan</span>
            <h2 class="section-title mt-3" style="font-size:clamp(1.8rem,3.2vw,2.5rem);">Pilih layanan yang Anda butuhkan</h2>
            <p class="section-sub mx-auto mt-2">Setiap paket sudah mencakup durasi sesi dan harga yang jelas. Terapis dipilihkan sistem sesuai tanggal Anda.</p>
        </div>

        <div class="row g-4">
            @forelse ($paket as $p)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                    @include('partials.hopewell.paket-card', [
                        'p' => $p,
                        'href' => route('pasien.reservasi.create', ['paket' => $p->id]),
                        'label' => 'Reservasi Paket Ini',
                    ])
                </div>
            @empty
                <div class="col-12 text-center text-muted">Paket layanan segera hadir.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================ CARA KERJA ============================ --}}
<section class="section-pad" id="cara-kerja" style="background:var(--clr-bg);">
    <div class="container contact-card">
        <div class="text-center mx-auto mb-5" style="max-width:640px;" data-aos="fade-up">
            <span class="eyebrow"><i class="bi bi-signpost-split-fill"></i> Alur Sederhana</span>
            <h2 class="section-title mt-3" style="font-size:clamp(1.8rem,3.2vw,2.5rem);">Tiga langkah menuju sesi terapi</h2>
        </div>
        <div class="hiw-wrap">
            <div class="hiw-line"></div>
            <div class="row g-4">
                <div class="col-md-4 hiw-step" data-aos="fade-up">
                    <div class="hiw-num">1</div>
                    <h5 class="font-display fw-bold">Pilih Paket &amp; Tanggal</h5>
                    <p class="text-muted">Tentukan paket layanan, lalu pilih tanggal yang cocok langsung dari kalender.</p>
                </div>
                <div class="col-md-4 hiw-step" data-aos="fade-up" data-aos-delay="120">
                    <div class="hiw-num">2</div>
                    <h5 class="font-display fw-bold">Terapis Muncul Otomatis</h5>
                    <p class="text-muted">Sistem menampilkan terapis yang tersedia. Bila tanggal itu penuh, Anda akan diberi tahu dan bisa memilih tanggal lain.</p>
                </div>
                <div class="col-md-4 hiw-step" data-aos="fade-up" data-aos-delay="240">
                    <div class="hiw-num">3</div>
                    <h5 class="font-display fw-bold">Bayar &amp; Datang</h5>
                    <p class="text-muted">Selesaikan pembayaran ke admin klinik. Setelah terverifikasi, jadwal Anda terkonfirmasi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================ TERAPIS ============================ --}}
<section class="section-pad" id="terapis">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;" data-aos="fade-up">
            <span class="eyebrow"><i class="bi bi-people-fill"></i> Tim Kami</span>
            <h2 class="section-title mt-3" style="font-size:clamp(1.8rem,3.2vw,2.5rem);">Terapis yang mendampingi Anda</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($terapis as $t)
                @php($inisial = \Illuminate\Support\Str::of($t->user->nama_lengkap)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode(''))
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 80 }}">
                    <div class="vol-card">
                        <div class="vol-img">
                            @if ($t->foto_profil)
                                <img src="{{ asset('storage/'.$t->foto_profil) }}" alt="{{ $t->user->nama_lengkap }}" loading="lazy" style="width:100%;aspect-ratio:4/4.8;object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="width:100%;aspect-ratio:4/4.8;background:var(--clr-primary-light);">
                                    <span class="font-display fw-bold" style="font-size:3.2rem;color:var(--clr-primary);">{{ $inisial }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="vol-overlay">
                            <div class="fw-bold font-display">{{ $t->user->nama_lengkap }}</div>
                            <div class="small opacity-75">{{ $t->spesialisasi ?: 'Terapis' }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">Informasi terapis segera hadir.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================ CTA ============================ --}}
<section class="section-pad pt-0" id="cta">
    <div class="container">
        <div class="cta-banner" data-aos="zoom-in">
            <i class="bi bi-heart-fill" style="font-size:2.4rem;"></i>
            <h2 class="mt-3">Mulai langkah pemulihan Anda hari ini.</h2>
            <p class="mb-4" style="opacity:.92;">Buat akun gratis, pilih paket, dan biarkan sistem mengatur sisanya.</p>
            <a href="{{ route('register') }}" class="btn-cta text-decoration-none d-inline-block"><i class="bi bi-person-plus me-2"></i>Daftar Sekarang</a>
        </div>
    </div>
</section>

@endsection
