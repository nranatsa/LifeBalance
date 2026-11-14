@extends('layouts.hopewell')

@section('title', 'Buat Reservasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('hopewell/assets/flatpickr/flatpickr.min.css') }}">
<style>
    .flatpickr-calendar.inline{width:100%;max-width:100%;box-shadow:none;border:1px solid #e2e8f0;border-radius:14px}
    .flatpickr-calendar.inline .flatpickr-innerContainer,.flatpickr-calendar.inline .flatpickr-rContainer,.flatpickr-calendar.inline .flatpickr-days,.flatpickr-calendar.inline .dayContainer{width:100%;min-width:100%;max-width:100%}
    .flatpickr-day.selected{background:var(--clr-primary);border-color:var(--clr-primary)}
    .flatpickr-day.flatpickr-disabled,.flatpickr-day.flatpickr-disabled:hover{color:#cbd5e1}
    .slot-box{border-radius:14px;padding:1rem 1.1rem;background:#f8fafc;border:1px dashed #cbd5e1}
    .slot-box.ok{background:#f0fdf4;border:1px solid #86efac}
    .slot-box.none{background:#fef2f2;border:1px solid #fecaca}
</style>
@endpush

@section('content')
<section class="page-banner">
    <div class="container">
        <h1 class="section-title" style="font-size:clamp(1.8rem,3vw,2.3rem);">Buat Reservasi</h1>
        <p class="section-sub mb-0">Pilih paket, lalu pilih tanggal. Terapis yang bisa melayani muncul otomatis.</p>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="hw-panel">
                    <h5 class="font-display fw-bold mb-3">1. Pilih Paket</h5>
                    <div class="list-group list-group-flush">
                        @forelse ($daftarPaket as $p)
                            <a href="{{ route('pasien.reservasi.create', ['paket' => $p->id]) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $paket?->id === $p->id ? 'active' : '' }}"
                               style="border-radius:10px;margin-bottom:6px;">
                                <span><strong>{{ $p->nama_paket }}</strong><br><small>{{ $p->durasi_menit }} menit</small></span>
                                <span class="fw-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            </a>
                        @empty
                            <p class="text-muted mb-0">Belum ada paket tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="hw-panel h-100">
                    <h5 class="font-display fw-bold mb-3">2. Pilih Tanggal</h5>

                    @if (! $paket)
                        <p class="text-muted mb-0">Pilih salah satu paket di sebelah kiri untuk membuka kalender.</p>
                    @elseif (empty($hariAktif))
                        <div class="alert alert-warning mb-0" style="border-radius:12px;">Belum ada jadwal praktik terapis yang diatur. Silakan hubungi klinik.</div>
                    @else
                        <form action="{{ route('pasien.reservasi.store') }}" method="POST" id="form-reservasi">
                            @csrf
                            <input type="hidden" name="paket_layanan_id" value="{{ $paket->id }}">
                            <input type="hidden" name="tanggal" id="tanggal">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <input type="text" id="kalender" class="d-none" aria-label="Kalender">
                                    <p class="small text-muted mt-2 mb-0">Tanggal yang abu-abu tidak ada praktik. Reservasi bisa dibuat sampai {{ $maxHari }} hari ke depan.</p>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Paket</label>
                                        <input type="text" class="form-control" value="{{ $paket->nama_paket }} ({{ $paket->durasi_menit }} menit)" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal</label>
                                        <input type="text" class="form-control" id="tanggal-label" placeholder="Belum dipilih" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Terapis</label>
                                        <input type="text" class="form-control" id="terapis" placeholder="Pilih tanggal terlebih dahulu" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jam</label>
                                        <input type="text" class="form-control" id="jam" placeholder="-" readonly>
                                    </div>

                                    <div class="slot-box mb-3 small" id="slot-info">Pilih tanggal di kalender.</div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Biaya</span>
                                        <strong style="color:var(--clr-primary)">Rp {{ number_format($paket->harga, 0, ',', '.') }}</strong>
                                    </div>

                                    <button type="submit" class="btn-hw-primary w-100 justify-content-center" id="btn-submit" disabled>
                                        Konfirmasi Reservasi <i class="bi bi-check2-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if ($paket && ! empty($hariAktif))
@push('scripts')
<script src="{{ asset('hopewell/assets/flatpickr/flatpickr.min.js') }}"></script>
<script>
(function () {
    const hariAktif = @json($hariAktif);          // 1 = Senin ... 7 = Minggu
    const urlSlot   = @json(route('pasien.reservasi.slot'));
    const paketId   = @json($paket->id);
    const maxHari   = @json($maxHari);

    flatpickr.localize({
        firstDayOfWeek: 1,
        weekdays: {
            shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
        },
        months: {
            shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        }
    });

    const elTanggal = document.getElementById('tanggal');
    const elLabel   = document.getElementById('tanggal-label');
    const elTerapis = document.getElementById('terapis');
    const elJam     = document.getElementById('jam');
    const elInfo    = document.getElementById('slot-info');
    const btn       = document.getElementById('btn-submit');
    let permintaan  = 0;

    function reset(pesan, kelas) {
        elTerapis.value = '';
        elJam.value = '';
        elTerapis.placeholder = kelas === 'none' ? 'Tidak ada terapis tersedia' : 'Pilih tanggal terlebih dahulu';
        elInfo.className = 'slot-box mb-3 small ' + (kelas || '');
        elInfo.textContent = pesan;
        btn.disabled = true;
    }

    function cekTanggal(dateStr) {
        const nomor = ++permintaan;
        elTanggal.value = dateStr;
        elLabel.value = dateStr;
        reset('Mencari terapis yang tersedia...');

        fetch(urlSlot + '?paket=' + paketId + '&tanggal=' + encodeURIComponent(dateStr), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json().then(j => ({ ok: r.ok, j })))
        .then(({ ok, j }) => {
            if (nomor !== permintaan) return;           // abaikan respons usang
            if (!ok) { reset(j.pesan || 'Tanggal tidak valid.', 'none'); return; }
            if (!j.tersedia) { reset(j.pesan, 'none'); return; }

            elLabel.value = j.tanggal_label;
            elTerapis.value = j.terapis;
            elJam.value = j.jam;
            elInfo.className = 'slot-box ok mb-3 small';
            elInfo.textContent = 'Terapis tersedia' + (j.spesialisasi ? ' (' + j.spesialisasi + ')' : '') + '. Jadwal final ditetapkan saat Anda mengonfirmasi.';
            btn.disabled = false;
        })
        .catch(() => { if (nomor === permintaan) reset('Gagal memeriksa ketersediaan. Coba pilih tanggal lagi.', 'none'); });
    }

    flatpickr('#kalender', {
        inline: true,
        dateFormat: 'Y-m-d',
        minDate: 'today',
        maxDate: new Date().fp_incr(maxHari),
        disable: [function (d) { return !hariAktif.includes(d.getDay() === 0 ? 7 : d.getDay()); }],
        onChange: function (selected, dateStr) { if (dateStr) cekTanggal(dateStr); }
    });
})();
</script>
@endpush
@endif
