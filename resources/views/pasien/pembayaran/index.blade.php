@extends('layouts.hopewell')

@section('title', 'Pembayaran')

@section('content')
<section class="page-banner">
    <div class="container">
        <h1 class="section-title" style="font-size:clamp(1.8rem,3vw,2.3rem);">Riwayat Pembayaran</h1>
        <p class="section-sub mb-0">Pembayaran dicatat langsung oleh admin klinik.</p>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="hw-panel">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Tanggal</th><th>Reservasi</th><th>Metode</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($pembayaran as $p)
                        <tr>
                            <td>{{ $p->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td><a href="{{ route('pasien.reservasi.show', $p->reservasi_id) }}">{{ $p->reservasi?->kode_booking }}</a><br><small class="text-muted">{{ $p->reservasi?->paket?->nama_paket }}</small></td>
                            <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                            <td>Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                            <td>@include('partials.hopewell.status-badge', ['status' => $p->status_pembayaran])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $pembayaran->links() }}</div>
        </div>
    </div>
</section>
@endsection
