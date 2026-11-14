@extends('layouts.hopewell')

@section('title', 'Beranda')

@section('content')
<section class="page-banner">
    <div class="container" data-aos="fade-up">
        <span class="eyebrow"><i class="bi bi-stars"></i> Halo, {{ auth()->user()->nama_lengkap }}</span>
        <h1 class="section-title mt-3" style="font-size:clamp(1.8rem,3.2vw,2.5rem);">Pilih paket, jadwal diatur otomatis</h1>
        <p class="section-sub">Pilih paket layanan yang Anda butuhkan. Sistem akan menentukan jadwal terdekat dan terapis yang tersedia untuk Anda.</p>
    </div>
</section>

@if ($reservasiMendatang->isNotEmpty())
<section class="pb-4">
    <div class="container">
        <h4 class="font-display fw-bold mb-3">Jadwal Anda yang akan datang</h4>
        <div class="row g-3">
            @foreach ($reservasiMendatang as $r)
                <div class="col-md-4">
                    <a href="{{ route('pasien.reservasi.show', $r) }}" class="text-decoration-none">
                        <div class="hw-panel h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong class="text-dark">{{ $r->paket->nama_paket }}</strong>
                                @include('partials.hopewell.status-badge', ['status' => $r->status_antrean])
                            </div>
                            <div class="text-muted small"><i class="bi bi-calendar-event"></i> {{ $r->tanggal_praktik->translatedFormat('l, d F Y') }}</div>
                            <div class="text-muted small"><i class="bi bi-clock"></i> {{ $r->jam }}</div>
                            <div class="text-muted small"><i class="bi bi-person-heart"></i> {{ $r->terapis->user->nama_lengkap }}</div>
                            <div class="text-muted small"><i class="bi bi-hash"></i> Antrean {{ $r->nomor_antrean }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-pad bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="eyebrow"><i class="bi bi-heart-pulse"></i> Paket Layanan</span>
            <h2 class="section-title mt-3" style="font-size:clamp(1.8rem,3.2vw,2.5rem);">Layanan yang tersedia</h2>
        </div>

        <div class="row g-4">
            @forelse ($paket as $p)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    @include('partials.hopewell.paket-card', ['p' => $p, 'href' => route('pasien.reservasi.create', ['paket' => $p->id])])
                </div>
            @empty
                <div class="col-12 text-center text-muted">Belum ada paket layanan yang dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
