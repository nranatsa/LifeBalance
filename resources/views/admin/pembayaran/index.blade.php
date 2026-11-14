@extends('layouts.spark')

@section('title', 'Pembayaran')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Verifikasi pembayaran dari pasien. Reservasi baru terjadwal setelah pembayaran lunas.')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select-custom" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach (['pending' => 'Menunggu', 'lunas' => 'Lunas', 'ditolak' => 'Ditolak'] as $v => $l)
                    <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Dikirim</th><th>Kode Booking</th><th>Pasien</th><th>Paket</th><th>Metode</th><th>Total</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($pembayaran as $p)
                <tr>
                    <td>{{ $p->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td class="table-order-id">{{ $p->reservasi->kode_booking }}</td>
                    <td>{{ $p->reservasi->pasien->user->nama_lengkap }}</td>
                    <td>{{ $p->reservasi->paket->nama_paket }}</td>
                    <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                    <td class="table-amount">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                    <td>@include('partials.spark.status-badge', ['status' => $p->status_pembayaran])</td>
                    <td class="text-center"><a href="{{ route('admin.pembayaran.show', $p) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pembayaran->links() }}</div>
</div>
@endsection
