@php($paket = $paket ?? null)
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label-custom" for="nama_paket">Nama Paket</label>
        <input type="text" id="nama_paket" name="nama_paket" class="form-control-custom" value="{{ old('nama_paket', $paket?->nama_paket) }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label-custom" for="durasi_menit">Durasi (menit)</label>
        <input type="number" id="durasi_menit" name="durasi_menit" class="form-control-custom" min="15" max="480" step="5" value="{{ old('durasi_menit', $paket?->durasi_menit ?? 60) }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label-custom" for="harga">Harga (Rp)</label>
        <input type="number" id="harga" name="harga" class="form-control-custom" min="0" step="1000" value="{{ old('harga', $paket ? (int) $paket->harga : '') }}" required>
    </div>
    <div class="col-12">
        <label class="form-label-custom" for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control-custom">{{ old('deskripsi', $paket?->deskripsi) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="foto_paket">Foto Paket</label>
        <input type="file" id="foto_paket" name="foto_paket" class="form-control-custom" accept="image/*">
        @if ($paket?->foto_paket)
            <img src="{{ asset('storage/'.$paket->foto_paket) }}" alt="" class="mt-2 rounded" style="max-height:90px">
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check-custom">
            <input type="hidden" name="status_publish" value="0">
            <input type="checkbox" class="form-check-input-custom" id="status_publish" name="status_publish" value="1" @checked(old('status_publish', $paket?->status_publish ?? true))>
            <label class="form-check-label" for="status_publish">Publish (tampil untuk pasien)</label>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn-custom btn-custom-primary"><i class="bi bi-check-lg"></i> Simpan</button>
    <a href="{{ route('admin.paket.index') }}" class="btn-custom btn-custom-light">Batal</a>
</div>
