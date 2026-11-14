@extends('layouts.spark')

@section('title', 'Catat Pembayaran')
@section('page_title', 'Catat Pembayaran')
@section('page_subtitle', 'Pilih sesi yang sudah selesai dan dibayar pasien.')

@section('content')
<div class="card border-light shadow-sm p-4">
    @if ($menunggu->isEmpty())
        <p class="text-muted mb-0">Tidak ada sesi selesai yang menunggu pembayaran. Tandai sesi selesai dulu di menu Antrean.</p>
    @else
    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label-custom" for="reservasi_id">Reservasi</label>
                <select name="reservasi_id" id="reservasi_id" class="form-select-custom" required>
                    <option value="">Pilih reservasi</option>
                    @foreach ($menunggu as $r)
                        <option value="{{ $r->id }}" data-harga="{{ (int) ($r->paket?->harga ?? 0) }}"
                            @selected(old('reservasi_id', $terpilih?->id) == $r->id)>
                            {{ $r->kode_booking }} - {{ $r->pasien?->user?->nama_lengkap ?? 'Pasien?' }} - {{ $r->paket?->nama_paket ?? 'Paket?' }}
                            ({{ $r->tanggal_praktik->translatedFormat('d M Y') }}, {{ $r->jam }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label-custom" for="metode_pembayaran">Metode</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select-custom" required>
                    @foreach (['tunai' => 'Tunai', 'transfer' => 'Transfer Bank', 'qris' => 'QRIS'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('metode_pembayaran') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label-custom" for="total_bayar">Total Dibayar (Rp)</label>
                <input type="number" name="total_bayar" id="total_bayar" min="0" step="1000" class="form-control-custom"
                    value="{{ old('total_bayar', (int) ($terpilih?->paket?->harga ?? 0)) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label-custom" for="bukti_pembayaran">Bukti (opsional)</label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control-custom" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-custom btn-custom-primary"><i class="bi bi-check-lg"></i> Simpan sebagai Lunas</button>
            <a href="{{ route('admin.pembayaran.index') }}" class="btn-custom btn-custom-light">Batal</a>
        </div>
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // isi otomatis total dari harga paket saat reservasi dipilih
    const sel = document.getElementById('reservasi_id');
    const total = document.getElementById('total_bayar');
    if (sel && total) {
        sel.addEventListener('change', function () {
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.dataset.harga) total.value = opt.dataset.harga;
        });
    }
</script>
@endpush
