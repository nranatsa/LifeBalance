@php($terapis = $terapis ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label-custom" for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control-custom" value="{{ old('nama_lengkap', $terapis?->user->nama_lengkap) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="spesialisasi">Spesialisasi</label>
        <input type="text" id="spesialisasi" name="spesialisasi" class="form-control-custom" value="{{ old('spesialisasi', $terapis?->spesialisasi) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control-custom" value="{{ old('email', $terapis?->user->email) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="no_telepon">No. Telepon</label>
        <input type="text" id="no_telepon" name="no_telepon" class="form-control-custom" value="{{ old('no_telepon', $terapis?->user->no_telepon) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="password">Password {{ $terapis ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" id="password" name="password" class="form-control-custom" minlength="8" @required(! $terapis)>
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="password_confirmation">Ulangi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" minlength="8">
    </div>
    <div class="col-md-6">
        <label class="form-label-custom" for="foto_profil">Foto Profil</label>
        <input type="file" id="foto_profil" name="foto_profil" class="form-control-custom" accept="image/*">
        @if ($terapis?->foto_profil)
            <img src="{{ asset('storage/'.$terapis->foto_profil) }}" class="mt-2 rounded-circle" style="height:64px;width:64px;object-fit:cover" alt="">
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check-custom">
            <input type="hidden" name="status_aktif" value="0">
            <input type="checkbox" class="form-check-input-custom" id="status_aktif" name="status_aktif" value="1" @checked(old('status_aktif', $terapis?->status_aktif ?? true))>
            <label class="form-check-label" for="status_aktif">Aktif (ikut penjadwalan otomatis)</label>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn-custom btn-custom-primary"><i class="bi bi-check-lg"></i> Simpan</button>
    <a href="{{ route('admin.terapis.index') }}" class="btn-custom btn-custom-light">Batal</a>
</div>
