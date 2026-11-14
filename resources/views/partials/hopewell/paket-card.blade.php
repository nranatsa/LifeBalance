{{-- Kartu paket layanan. Variabel: $p (PaketLayanan), $href (tautan tombol), $label (opsional) --}}
<div class="cause-card h-100">
    <div class="cause-img">
        @if ($p->foto_paket)
            <img src="{{ asset('storage/'.$p->foto_paket) }}" alt="{{ $p->nama_paket }}" loading="lazy">
        @else
            <div class="d-flex align-items-center justify-content-center h-100" style="background:var(--clr-primary-light);">
                <i class="bi bi-heart-pulse" style="font-size:3.5rem;color:var(--clr-primary);"></i>
            </div>
        @endif
        <span class="cause-tag">{{ $p->durasi_menit }} menit</span>
    </div>
    <div class="cause-body">
        <h5 class="font-display fw-bold">{{ $p->nama_paket }}</h5>
        <p class="text-muted small">{{ \Illuminate\Support\Str::limit($p->deskripsi, 110) }}</p>
        <div class="cause-meta mb-3"><span class="raised">Rp {{ number_format($p->harga, 0, ',', '.') }}</span></div>
        <a href="{{ $href }}" class="btn-hw-primary w-100 justify-content-center">{{ $label ?? 'Pilih Paket' }} <i class="bi bi-arrow-right"></i></a>
    </div>
</div>
