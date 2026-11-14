@extends('layouts.spark')

@section('title', 'Pasien')
@section('page_title', 'Data Pasien')
@section('page_subtitle', 'Pasien mendaftar sendiri melalui halaman registrasi.')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control-custom" placeholder="Cari nama, email, atau telepon...">
        </div>
        <div class="col-auto"><button class="btn-custom btn-custom-primary" type="submit"><i class="bi bi-search"></i> Cari</button></div>
    </form>

    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Pasien</th><th>Telepon</th><th>JK</th><th>Umur</th><th>Reservasi</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($pasien as $p)
                <tr>
                    <td>
                        <div class="table-user-cell">
                            <img src="{{ $p->foto_profil ? asset('storage/'.$p->foto_profil) : asset('spark/assets/images/avatar.png') }}" class="table-user-avatar" alt="">
                            <div>
                                <div class="table-user-name">{{ $p->user->nama_lengkap }}</div>
                                <div class="table-user-sub">{{ $p->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $p->user->no_telepon }}</td>
                    <td>{{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $p->umur }} th</td>
                    <td>{{ $p->reservasi_count }}</td>
                    <td class="text-center"><a href="{{ route('admin.pasien.show', $p) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pasien.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pasien->links() }}</div>
</div>
@endsection
