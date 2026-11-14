@extends('layouts.spark')

@section('title', 'Pembayaran')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Pasien membayar di klinik setelah sesi selesai; admin mencatatnya di sini.')
@section('page_actions')
    <a href="{{ route('admin.pembayaran.create') }}" class="btn-custom btn-custom-primary">
        <i class="bi bi-plus-lg"></i> Catat Pembayaran
        @if ($menungguBayar > 0)<span class="badge bg-light text-dark ms-1">{{ $menungguBayar }}</span>@endif
    </a>
@endsection

@section('content')
<div class="card border-light shadow-sm p-4">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select-custom" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach (['lunas' => 'Lunas', 'ditolak' => 'Dibatalkan'] as $v => $l)
                    <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Dicatat</th><th>Kode Booking</th><th>Pasien</th><th>Paket</th><th>Metode</th><th>Total</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($pembayaran as $p)
                <tr>
                    <td>{{ $p->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td class="table-order-id">{{ $p->reservasi?->kode_booking ?? '-' }}</td>
                    <td>{{ $p->reservasi?->pasien?->user?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $p->reservasi?->paket?->nama_paket ?? '-' }}</td>
                    <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                    <td class="table-amount">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                    <td>@include('partials.spark.status-badge', ['status' => $p->status_pembayaran])</td>
                    <td class="text-center"><a href="{{ route('admin.pembayaran.show', $p) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pembayaran tercatat.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pembayaran->links() }}</div>
</div>
@endsection
