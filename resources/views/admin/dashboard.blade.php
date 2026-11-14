@extends('layouts.spark')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan operasional klinik hari ini, '.now()->translatedFormat('l, d F Y'))

@section('content')
<div class="row g-4 mb-4">
    @php
        $kartu = [
            ['Antrean Hari Ini', $stat['antrean_hari_ini'], 'bi-list-ol'],
            ['Sesi Belum Dibayar', $stat['belum_dibayar'], 'bi-hourglass-split'],
            ['Pendapatan Bulan Ini', 'Rp '.number_format($stat['pendapatan_bulan_ini'], 0, ',', '.'), 'bi-cash-coin'],
            ['Pasien Terdaftar', $stat['pasien'], 'bi-people'],
            ['Terapis Aktif', $stat['terapis'], 'bi-person-heart'],
            ['Paket Dipublish', $stat['paket'], 'bi-box-seam'],
        ];
    @endphp
    @foreach ($kartu as [$label, $nilai, $ikon])
        <div class="col-md-6 col-xl-4">
            <div class="card card-stat">
                <div class="card-header">
                    <span class="stat-label">{{ $label }}</span>
                    <i class="bi {{ $ikon }} fs-4"></i>
                </div>
                <div class="stat-value">{{ $nilai }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Antrean Hari Ini</h5>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>No</th><th>Jam</th><th>Pasien</th><th>Paket</th><th>Terapis</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($antreanHariIni as $r)
                        <tr>
                            <td class="table-order-id">#{{ $r->nomor_antrean }}</td>
                            <td>{{ $r->jam }}</td>
                            <td>{{ ($r->pasien?->user?->nama_lengkap ?? '-') }}</td>
                            <td>{{ ($r->paket?->nama_paket ?? '-') }}</td>
                            <td>{{ ($r->terapis?->user?->nama_lengkap ?? '-') }}</td>
                            <td>@include('partials.spark.status-badge', ['status' => $r->status_antrean])</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada antrean hari ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-light shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Sesi Selesai, Belum Dibayar</h5>
                <a href="{{ route('admin.pembayaran.create') }}" class="small">Catat pembayaran</a>
            </div>
            @forelse ($menungguBayar as $r)
                <a href="{{ route('admin.pembayaran.create', ['reservasi' => $r->id]) }}" class="d-block text-decoration-none py-2 border-bottom">
                    <div class="fw-semibold">{{ $r->pasien?->user?->nama_lengkap ?? '(data pasien tidak ditemukan)' }}</div>
                    <div class="small text-muted">{{ $r->paket?->nama_paket ?? '-' }} &middot; {{ $r->tanggal_praktik->translatedFormat('d M') }} {{ $r->jam }} &middot; Rp {{ number_format($r->paket?->harga ?? 0, 0, ',', '.') }}</div>
                </a>
            @empty
                <p class="text-muted mb-0">Semua sesi yang selesai sudah dibayar.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
