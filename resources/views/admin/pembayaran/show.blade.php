@extends('layouts.spark')

@section('title', 'Detail Pembayaran')
@section('page_title', 'Detail Pembayaran')
@section('page_subtitle', $pembayaran->reservasi->kode_booking)

@section('content')
@php($r = $pembayaran->reservasi)
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Informasi</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Pasien</dt><dd class="col-sm-8">{{ $r->pasien->user->nama_lengkap }}</dd>
                <dt class="col-sm-4">Paket</dt><dd class="col-sm-8">{{ $r->paket->nama_paket }}</dd>
                <dt class="col-sm-4">Jadwal</dt><dd class="col-sm-8">{{ $r->tanggal_praktik->translatedFormat('l, d F Y') }}, {{ $r->jam }}</dd>
                <dt class="col-sm-4">Terapis</dt><dd class="col-sm-8">{{ $r->terapis->user->nama_lengkap }}</dd>
                <dt class="col-sm-4">Metode</dt><dd class="col-sm-8">{{ ucfirst($pembayaran->metode_pembayaran) }}</dd>
                <dt class="col-sm-4">Total</dt><dd class="col-sm-8 fw-bold">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</dd>
                <dt class="col-sm-4">Status</dt><dd class="col-sm-8">@include('partials.spark.status-badge', ['status' => $pembayaran->status_pembayaran])</dd>
                @if ($pembayaran->verifikator)
                    <dt class="col-sm-4">Diproses oleh</dt><dd class="col-sm-8">{{ $pembayaran->verifikator->nama_lengkap }}</dd>
                @endif
                @if ($pembayaran->tanggal_bayar)
                    <dt class="col-sm-4">Tanggal Lunas</dt><dd class="col-sm-8">{{ $pembayaran->tanggal_bayar->translatedFormat('d M Y H:i') }}</dd>
                @endif
            </dl>

            @if ($pembayaran->status_pembayaran === 'pending')
                <div class="d-flex gap-2 mt-4">
                    <form action="{{ route('admin.pembayaran.verifikasi', $pembayaran) }}" method="POST" data-confirm="Tandai pembayaran ini LUNAS?">
                        @csrf @method('PATCH')
                        <button class="btn-custom btn-custom-primary" type="submit"><i class="bi bi-check-lg"></i> Verifikasi Lunas</button>
                    </form>
                    <form action="{{ route('admin.pembayaran.tolak', $pembayaran) }}" method="POST" data-confirm="Tolak pembayaran ini?">
                        @csrf @method('PATCH')
                        <button class="btn-custom btn-custom-outline-danger" type="submit"><i class="bi bi-x-lg"></i> Tolak</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Bukti Pembayaran</h5>
            @if ($pembayaran->bukti_pembayaran)
                @php($url = asset('storage/'.$pembayaran->bukti_pembayaran))
                @if (Str::endsWith(strtolower($pembayaran->bukti_pembayaran), '.pdf'))
                    <a href="{{ $url }}" target="_blank" class="btn-custom btn-custom-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Buka PDF</a>
                @else
                    <a href="{{ $url }}" target="_blank"><img src="{{ $url }}" class="img-fluid rounded border" alt="Bukti pembayaran"></a>
                @endif
            @else
                <p class="text-muted mb-0">Tidak ada bukti (pembayaran tunai di klinik).</p>
            @endif
        </div>
    </div>
</div>
@endsection
