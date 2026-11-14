@extends('layouts.hopewell')

@section('title', 'Detail Reservasi')

@section('content')
@php($bayar = $reservasi->pembayaran)
<section class="page-banner">
    <div class="container">
        <a href="{{ route('pasien.reservasi.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h1 class="section-title mt-2" style="font-size:clamp(1.8rem,3vw,2.3rem);">Reservasi {{ $reservasi->kode_booking }}</h1>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="hw-panel h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-display fw-bold mb-0">{{ $reservasi->paket->nama_paket }}</h5>
                        @include('partials.hopewell.status-badge', ['status' => $reservasi->status_antrean])
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Tanggal</dt><dd class="col-sm-8">{{ $reservasi->tanggal_praktik->translatedFormat('l, d F Y') }}</dd>
                        <dt class="col-sm-4">Jam</dt><dd class="col-sm-8">{{ $reservasi->jam }}</dd>
                        <dt class="col-sm-4">Terapis</dt><dd class="col-sm-8">{{ $reservasi->terapis->user->nama_lengkap }}</dd>
                        <dt class="col-sm-4">Nomor Antrean</dt><dd class="col-sm-8">#{{ $reservasi->nomor_antrean }}</dd>
                        <dt class="col-sm-4">Durasi</dt><dd class="col-sm-8">{{ $reservasi->paket->durasi_menit }} menit</dd>
                        <dt class="col-sm-4">Biaya</dt><dd class="col-sm-8 fw-bold">Rp {{ number_format($reservasi->paket->harga, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hw-panel h-100">
                    <h5 class="font-display fw-bold mb-3">Pembayaran</h5>

                    @if ($bayar)
                        <p class="mb-1">Status terakhir: @include('partials.hopewell.status-badge', ['status' => $bayar->status_pembayaran])</p>
                        <p class="small text-muted">Metode: {{ ucfirst($bayar->metode_pembayaran) }} &middot; dikirim {{ $bayar->created_at->translatedFormat('d M Y H:i') }}</p>
                    @endif

                    @if ($reservasi->status_antrean === 'menunggu_pembayaran')
                        @if ($bayar?->status_pembayaran === 'pending')
                            <div class="alert alert-info mb-3" style="border-radius:12px;">Pembayaran Anda sedang diverifikasi admin.</div>
                        @else
                            @if ($bayar?->status_pembayaran === 'ditolak')
                                <div class="alert alert-warning" style="border-radius:12px;">Pembayaran sebelumnya ditolak. Silakan kirim ulang bukti pembayaran.</div>
                            @endif
                            <a href="{{ route('pasien.pembayaran.create', $reservasi) }}" class="btn-hw-primary w-100 justify-content-center mb-2">Bayar Sekarang <i class="bi bi-wallet2"></i></a>
                        @endif

                        <form action="{{ route('pasien.reservasi.destroy', $reservasi) }}" method="POST" data-confirm="Batalkan reservasi ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Batalkan Reservasi</button>
                        </form>
                    @elseif ($reservasi->status_antrean === 'terjadwal')
                        <div class="alert alert-success mb-0" style="border-radius:12px;">Pembayaran lunas. Datang sesuai jadwal di atas. Untuk pembatalan, hubungi admin klinik.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
