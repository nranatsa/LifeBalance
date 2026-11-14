@extends('layouts.spark')

@section('title', 'Detail Pembayaran')
@section('page_title', 'Detail Pembayaran')
@section('page_subtitle', $pembayaran->reservasi?->kode_booking ?? '')

@section('content')
@php($r = $pembayaran->reservasi)
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Informasi</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Pasien</dt><dd class="col-sm-8">{{ $r?->pasien?->user?->nama_lengkap ?? '-' }}</dd>
                <dt class="col-sm-4">Paket</dt><dd class="col-sm-8">{{ $r?->paket?->nama_paket ?? '-' }}</dd>
                <dt class="col-sm-4">Jadwal</dt>
                <dd class="col-sm-8">{{ $r?->exists ? $r->tanggal_praktik->translatedFormat('l, d F Y').', '.$r->jam : '-' }}</dd>
                <dt class="col-sm-4">Terapis</dt><dd class="col-sm-8">{{ $r?->terapis?->user?->nama_lengkap ?? '-' }}</dd>
                <dt class="col-sm-4">Metode</dt><dd class="col-sm-8">{{ ucfirst($pembayaran->metode_pembayaran) }}</dd>
                <dt class="col-sm-4">Total</dt><dd class="col-sm-8 fw-bold">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</dd>
                <dt class="col-sm-4">Status</dt><dd class="col-sm-8">@include('partials.spark.status-badge', ['status' => $pembayaran->status_pembayaran])</dd>
                <dt class="col-sm-4">Dicatat oleh</dt><dd class="col-sm-8">{{ $pembayaran->verifikator?->nama_lengkap ?? '-' }}</dd>
                <dt class="col-sm-4">Tanggal Bayar</dt><dd class="col-sm-8">{{ $pembayaran->tanggal_bayar?->translatedFormat('d M Y H:i') ?? '-' }}</dd>
            </dl>

            @if ($pembayaran->status_pembayaran === 'lunas')
                <form action="{{ route('admin.pembayaran.batalkan', $pembayaran) }}" method="POST" class="mt-4"
                    data-confirm="Batalkan pembayaran ini? Sesi akan tercatat belum dibayar.">
                    @csrf @method('PATCH')
                    <button class="btn-custom btn-custom-outline-danger" type="submit"><i class="bi bi-x-lg"></i> Batalkan Pembayaran (salah catat)</button>
                </form>
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
                <p class="text-muted mb-0">Tidak ada bukti terlampir.</p>
            @endif
        </div>
    </div>
</div>
@endsection
