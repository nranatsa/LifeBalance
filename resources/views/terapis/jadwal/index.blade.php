@extends('layouts.spark')

@section('title', 'Jadwal Saya')
@section('page_title', 'Jadwal Saya')
@section('page_subtitle', 'Jadwal dibuat otomatis oleh sistem. Anda mengonfirmasi sesi yang sudah selesai.')

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-light shadow-sm p-4">
            <form method="GET" class="row g-2 mb-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-custom">Tanggal</label>
                    <input type="text" name="tanggal" data-datepicker value="{{ $tanggal }}" class="form-control-custom" placeholder="Semua jadwal mendatang">
                </div>
                <div class="col-auto d-flex gap-2">
                    <button class="btn-custom btn-custom-primary" type="submit"><i class="bi bi-funnel"></i> Tampilkan</button>
                    <a href="{{ route('terapis.jadwal.index') }}" class="btn-custom btn-custom-light">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Tanggal</th><th>Jam</th><th>No</th><th>Pasien</th><th>Paket</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
                    <tbody>
                    @forelse ($reservasi as $r)
                        <tr>
                            <td>{{ $r->tanggal_praktik->translatedFormat('D, d M Y') }}</td>
                            <td>{{ $r->jam }}</td>
                            <td class="table-order-id">#{{ $r->nomor_antrean }}</td>
                            <td>{{ $r->pasien->user->nama_lengkap }}</td>
                            <td>{{ $r->paket->nama_paket }}</td>
                            <td>@include('partials.spark.status-badge', ['status' => $r->status_antrean])</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('terapis.jadwal.show', $r) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a>
                                    @if (in_array($r->status_antrean, ['terjadwal', 'berlangsung']) && ! $r->tanggal_praktik->isFuture())
                                        <form action="{{ route('terapis.jadwal.selesai', $r) }}" method="POST" data-confirm="Tandai sesi ini selesai?">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="table-btn-action" title="Tandai selesai"><i class="bi bi-check2-circle"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pasien terjadwal.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $reservasi->links() }}</div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-light shadow-sm p-4">
            <h5 class="card-title mb-3">Jam Praktik Mingguan</h5>
            @forelse ($jamPraktik as $hari => $daftar)
                <div class="py-2 border-bottom">
                    <div class="fw-semibold">{{ $hari }}</div>
                    @foreach ($daftar as $j)
                        <div class="small text-muted">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} &middot; kuota {{ $j->kuota_harian }}</div>
                    @endforeach
                </div>
            @empty
                <p class="text-muted mb-0">Admin belum mengatur jam praktik Anda.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
