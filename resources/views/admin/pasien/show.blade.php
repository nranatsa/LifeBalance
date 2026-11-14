@extends('layouts.spark')

@section('title', $pasien->user->nama_lengkap)
@section('page_title', $pasien->user->nama_lengkap)
@section('page_subtitle', 'Detail pasien dan riwayat reservasi')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Biodata</h5>
            <dl class="mb-0">
                <dt>Email</dt><dd>{{ $pasien->user->email }}</dd>
                <dt>Telepon</dt><dd>{{ $pasien->user->no_telepon }}</dd>
                <dt>Tanggal Lahir</dt><dd>{{ $pasien->tanggal_lahir->translatedFormat('d F Y') }} ({{ $pasien->umur }} th)</dd>
                <dt>Jenis Kelamin</dt><dd>{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                <dt>Alamat</dt><dd>{{ $pasien->alamat }}</dd>
                <dt>Catatan Medis</dt><dd class="mb-0">{{ $pasien->catatan_medis ?: '-' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Riwayat Reservasi</h5>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Kode</th><th>Tanggal</th><th>Paket</th><th>Terapis</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($reservasi as $r)
                        <tr>
                            <td class="table-order-id"><a href="{{ route('admin.antrean.show', $r) }}">{{ $r->kode_booking }}</a></td>
                            <td>{{ $r->tanggal_praktik->translatedFormat('d M Y') }}<br><small class="text-muted">{{ $r->jam }}</small></td>
                            <td>{{ $r->paket->nama_paket }}</td>
                            <td>{{ $r->terapis->user->nama_lengkap }}</td>
                            <td>@include('partials.spark.status-badge', ['status' => $r->status_antrean])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada reservasi.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $reservasi->links() }}</div>
        </div>
    </div>
</div>
@endsection
