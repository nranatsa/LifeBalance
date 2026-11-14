@extends('layouts.spark')

@section('title', 'Paket Layanan')
@section('page_title', 'Paket Layanan')
@section('page_subtitle', 'Paket yang dipublish akan tampil di halaman pasien.')
@section('page_actions')
    <a href="{{ route('admin.paket.create') }}" class="btn-custom btn-custom-primary"><i class="bi bi-plus-lg"></i> Tambah Paket</a>
@endsection

@section('content')
<div class="card border-light shadow-sm p-4">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Paket</th><th>Durasi</th><th>Harga</th><th>Reservasi</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($paket as $p)
                <tr>
                    <td>
                        <div class="table-user-cell">
                            @if ($p->foto_paket)
                                <img src="{{ asset('storage/'.$p->foto_paket) }}" class="table-user-avatar" alt="">
                            @endif
                            <div class="table-user-name">{{ $p->nama_paket }}</div>
                        </div>
                    </td>
                    <td>{{ $p->durasi_menit }} menit</td>
                    <td class="table-amount">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td>{{ $p->reservasi_count }}</td>
                    <td><span class="badge-table {{ $p->status_publish ? 'success' : 'pending' }}">{{ $p->status_publish ? 'Publish' : 'Draft' }}</span></td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.paket.show', $p) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.paket.edit', $p) }}" class="table-btn-action" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.paket.destroy', $p) }}" method="POST" data-confirm="Hapus paket ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="table-btn-action delete" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada paket layanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $paket->links() }}</div>
</div>
@endsection
