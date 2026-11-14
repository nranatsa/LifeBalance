@extends('layouts.hopewell')

@section('title', 'Reservasi Saya')

@section('content')
<section class="page-banner">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="section-title" style="font-size:clamp(1.8rem,3vw,2.3rem);">Reservasi Saya</h1>
            <p class="section-sub mb-0">Riwayat dan jadwal terapi Anda.</p>
        </div>
        <a href="{{ route('pasien.reservasi.create') }}" class="btn-hw-primary">Reservasi Baru <i class="bi bi-plus-lg"></i></a>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="hw-panel">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Kode</th><th>Jadwal</th><th>Paket</th><th>Terapis</th><th>Antrean</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($reservasi as $r)
                        <tr>
                            <td class="fw-semibold">{{ $r->kode_booking }}</td>
                            <td>{{ $r->tanggal_praktik->translatedFormat('d M Y') }}<br><small class="text-muted">{{ $r->jam }}</small></td>
                            <td>{{ ($r->paket?->nama_paket ?? '-') }}</td>
                            <td>{{ ($r->terapis?->user?->nama_lengkap ?? '-') }}</td>
                            <td>#{{ $r->nomor_antrean }}</td>
                            <td>@include('partials.hopewell.status-badge', ['status' => $r->status_antrean])</td>
                            <td class="text-end"><a href="{{ route('pasien.reservasi.show', $r) }}" class="btn btn-sm btn-outline-success">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Anda belum memiliki reservasi.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $reservasi->links() }}</div>
        </div>
    </div>
</section>
@endsection
