@extends('layouts.spark')

@section('title', 'Terapis')
@section('page_title', 'Terapis')
@section('page_subtitle', 'Kelola akun dan status terapis.')
@section('page_actions')
    <a href="{{ route('admin.terapis.create') }}" class="btn-custom btn-custom-primary"><i class="bi bi-plus-lg"></i> Tambah Terapis</a>
@endsection

@section('content')
<div class="card border-light shadow-sm p-4">
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Terapis</th><th>Spesialisasi</th><th>Telepon</th><th>Slot Jadwal</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($terapis as $t)
                <tr>
                    <td>
                        <div class="table-user-cell">
                            <img src="{{ $t->foto_profil ? asset('storage/'.$t->foto_profil) : asset('spark/assets/images/avatar.png') }}" class="table-user-avatar" alt="">
                            <div>
                                <div class="table-user-name">{{ $t->user->nama_lengkap }}</div>
                                <div class="table-user-sub">{{ $t->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $t->spesialisasi ?: '-' }}</td>
                    <td>{{ $t->user->no_telepon }}</td>
                    <td>{{ $t->jadwal_praktik_count }}</td>
                    <td><span class="badge-table {{ $t->status_aktif ? 'success' : 'failed' }}">{{ $t->status_aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.terapis.show', $t) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.terapis.edit', $t) }}" class="table-btn-action" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.terapis.destroy', $t) }}" method="POST" data-confirm="Hapus / nonaktifkan terapis ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="table-btn-action delete" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada terapis.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $terapis->links() }}</div>
</div>
@endsection
