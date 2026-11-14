@extends('layouts.spark')

@section('title', $terapis->user->nama_lengkap)
@section('page_title', $terapis->user->nama_lengkap)
@section('page_subtitle', $terapis->spesialisasi ?: 'Terapis')
@section('page_actions')
    <a href="{{ route('admin.terapis.edit', $terapis) }}" class="btn-custom btn-custom-primary"><i class="bi bi-pencil"></i> Ubah</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Jam Praktik</h5>
            @forelse ($terapis->jadwalPraktik->sortBy(fn ($j) => array_search($j->hari, \App\Models\JadwalPraktikTerapis::HARI)) as $j)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="fw-semibold">{{ $j->hari }}</span>
                    <span>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} <small class="text-muted">(kuota {{ $j->kuota_harian }})</small></span>
                </div>
            @empty
                <p class="text-muted mb-0">Belum ada jam praktik. Atur di menu Jadwal Praktik.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Reservasi Mendatang</h5>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Tanggal</th><th>Jam</th><th>Pasien</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($reservasi as $r)
                        <tr>
                            <td>{{ $r->tanggal_praktik->translatedFormat('d M Y') }}</td>
                            <td>{{ $r->jam }}</td>
                            <td>{{ ($r->pasien?->user?->nama_lengkap ?? '-') }}</td>
                            <td>@include('partials.spark.status-badge', ['status' => $r->status_antrean])</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada reservasi.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
