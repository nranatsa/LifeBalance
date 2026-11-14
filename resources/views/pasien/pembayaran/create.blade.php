@extends('layouts.hopewell')

@section('title', 'Pembayaran')

@section('content')
<section class="page-banner">
    <div class="container">
        <a href="{{ route('pasien.reservasi.show', $reservasi) }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h1 class="section-title mt-2" style="font-size:clamp(1.8rem,3vw,2.3rem);">Pembayaran</h1>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="hw-panel h-100">
                    <h5 class="font-display fw-bold mb-3">Ringkasan</h5>
                    <p class="mb-1"><strong>{{ $reservasi->paket->nama_paket }}</strong></p>
                    <p class="small text-muted mb-3">{{ $reservasi->tanggal_praktik->translatedFormat('l, d F Y') }}, {{ $reservasi->jam }}</p>
                    <div class="fs-3 fw-bold" style="color:var(--clr-primary)">Rp {{ number_format($reservasi->paket->harga, 0, ',', '.') }}</div>
                    <p class="small text-muted mt-3 mb-0">Lakukan pembayaran ke rekening/QRIS klinik atau bayar tunai di klinik, lalu unggah bukti di sini. Admin akan memverifikasi pembayaran Anda.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hw-panel">
                    <form action="{{ route('pasien.pembayaran.store', $reservasi) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metode" class="form-select" required>
                                <option value="transfer" @selected(old('metode_pembayaran') === 'transfer')>Transfer Bank</option>
                                <option value="qris" @selected(old('metode_pembayaran') === 'qris')>QRIS</option>
                                <option value="tunai" @selected(old('metode_pembayaran') === 'tunai')>Tunai di Klinik</option>
                            </select>
                        </div>
                        <div class="mb-4" id="wrap-bukti">
                            <label class="form-label fw-semibold">Bukti Pembayaran (JPG/PNG/PDF, maks. 4 MB)</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <button type="submit" class="btn-hw-primary">Kirim Pembayaran <i class="bi bi-send"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const metode = document.getElementById('metode');
    const wrap = document.getElementById('wrap-bukti');
    function toggleBukti() { wrap.style.display = metode.value === 'tunai' ? 'none' : ''; }
    metode.addEventListener('change', toggleBukti);
    toggleBukti();
</script>
@endpush
