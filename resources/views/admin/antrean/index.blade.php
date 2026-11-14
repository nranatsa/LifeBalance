@extends('layouts.spark')

@section('title', 'Antrean')
@section('page_title', 'Antrean Pasien')
@section('page_subtitle', 'Jadwal dan nomor antrean dibuat otomatis oleh sistem.')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label-custom">Tanggal</label>
            <input type="text" name="tanggal" data-datepicker value="{{ $tanggal === 'semua' ? '' : $tanggal }}" class="form-control-custom" placeholder="Semua tanggal">
        </div>
        <div class="col-md-3">
            <label class="form-label-custom">Terapis</label>
            <select name="terapis_id" class="form-select-custom">
                <option value="">Semua terapis</option>
                @foreach ($daftarTerapis as $t)
                    <option value="{{ $t->id }}" @selected(request('terapis_id') == $t->id)>{{ $t->user->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-custom">Status</label>
            <select name="status" class="form-select-custom">
                <option value="">Semua status</option>
                @foreach (\App\Models\ReservasiAntrean::STATUS as $v => $l)
                    <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto d-flex gap-2">
            <button class="btn-custom btn-custom-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
            <a class="btn-custom btn-custom-light" href="{{ route('admin.antrean.index', ['tanggal' => 'semua']) }}">Semua</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>No</th><th>Tanggal / Jam</th><th>Kode</th><th>Pasien</th><th>Paket</th><th>Terapis</th><th>Bayar</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse ($antrean as $r)
                <tr>
                    <td class="table-order-id">#{{ $r->nomor_antrean }}</td>
                    <td>{{ $r->tanggal_praktik->translatedFormat('d M Y') }}<br><small class="text-muted">{{ $r->jam }}</small></td>
                    <td>{{ $r->kode_booking }}</td>
                    <td>{{ ($r->pasien?->user?->nama_lengkap ?? '-') }}</td>
                    <td>{{ ($r->paket?->nama_paket ?? '-') }}</td>
                    <td>{{ ($r->terapis?->user?->nama_lengkap ?? '-') }}</td>
                    <td>
                        @if ($r->pembayaran)
                            @include('partials.spark.status-badge', ['status' => $r->pembayaran->status_pembayaran])
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>@include('partials.spark.status-badge', ['status' => $r->status_antrean])</td>
                    <td class="text-center"><a href="{{ route('admin.antrean.show', $r) }}" class="table-btn-action" title="Detail"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada antrean pada filter ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $antrean->links() }}</div>
</div>
@endsection
