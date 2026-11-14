@extends('layouts.spark')

@section('title', 'Detail Antrean')
@section('page_title', 'Antrean #'.$reservasi->nomor_antrean)
@section('page_subtitle', $reservasi->kode_booking)

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Detail Reservasi</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Pasien</dt><dd class="col-sm-8"><a href="{{ $reservasi->pasien->exists ? route('admin.pasien.show', $reservasi->pasien) : '#' }}">{{ $reservasi->pasien?->user?->nama_lengkap ?? '(data pasien tidak ditemukan)' }}</a></dd>
                <dt class="col-sm-4">Paket</dt><dd class="col-sm-8">{{ $reservasi->paket?->nama_paket ?? '-' }} ({{ $reservasi->paket?->durasi_menit ?? '-' }} menit)</dd>
                <dt class="col-sm-4">Terapis</dt><dd class="col-sm-8">{{ $reservasi->terapis?->user?->nama_lengkap ?? '-' }}</dd>
                <dt class="col-sm-4">Jadwal</dt><dd class="col-sm-8">{{ $reservasi->tanggal_praktik->translatedFormat('l, d F Y') }}, {{ $reservasi->jam }}</dd>
                <dt class="col-sm-4">Status</dt><dd class="col-sm-8">@include('partials.spark.status-badge', ['status' => $reservasi->status_antrean])</dd>
                <dt class="col-sm-4">Pembayaran</dt>
                <dd class="col-sm-8">
                    @if ($reservasi->pembayaran)
                        @include('partials.spark.status-badge', ['status' => $reservasi->pembayaran->status_pembayaran])
                        <a href="{{ route('admin.pembayaran.show', $reservasi->pembayaran) }}" class="ms-2 small">Lihat</a>
                    @else
                        <span class="text-muted">Belum ada pembayaran</span>
                    @endif
                    @if ($reservasi->status_antrean === 'selesai' && ! $reservasi->pembayaranLunas)
                        <a href="{{ route('admin.pembayaran.create', ['reservasi' => $reservasi->id]) }}" class="btn-custom btn-custom-primary btn-sm mt-2 d-inline-block"><i class="bi bi-wallet2"></i> Catat Pembayaran</a>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Ubah Status</h5>
            @forelse ($transisi as $status)
                <form action="{{ route('admin.antrean.status', $reservasi) }}" method="POST" class="mb-2"
                    @if ($status === 'batal') data-confirm="Tolak / batalkan booking ini? Slot akan dibebaskan." @endif>
                    @csrf @method('PATCH')
                    <input type="hidden" name="status_antrean" value="{{ $status }}">
                    <button type="submit" class="btn-custom {{ $status === 'batal' ? 'btn-custom-outline-danger' : 'btn-custom-primary' }} w-100">
                        {{ $status === 'batal' ? 'Tolak / Batalkan Booking' : 'Tandai '.\App\Models\ReservasiAntrean::STATUS[$status] }}
                    </button>
                </form>
            @empty
                <p class="text-muted mb-0">Antrean ini sudah final, status tidak dapat diubah.</p>
            @endforelse
            @if ($reservasi->status_antrean === 'selesai')
                <p class="small text-muted mt-3 mb-0">Sesi selesai. Catat pembayaran pasien dari kolom Pembayaran di samping.</p>
            @endif
        </div>
    </div>
</div>
@endsection
