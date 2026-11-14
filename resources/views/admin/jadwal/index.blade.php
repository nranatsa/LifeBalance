@extends('layouts.spark')

@section('title', 'Jadwal Praktik')
@section('page_title', 'Jadwal Praktik Terapis')
@section('page_subtitle', 'Jam praktik mingguan ini dipakai sistem untuk menjadwalkan pasien secara otomatis.')

@section('content')
{{-- Tambah jadwal --}}
<div class="card border-light shadow-sm p-4 mb-4">
    <h5 class="card-title mb-3">Tambah Jam Praktik</h5>
    <form action="{{ route('admin.jadwal.store') }}" method="POST" class="row g-3 align-items-end">
        @csrf
        <div class="col-md-3">
            <label class="form-label-custom">Terapis</label>
            <select name="terapis_id" class="form-select-custom" required>
                <option value="">Pilih terapis</option>
                @foreach ($terapis as $t)
                    <option value="{{ $t->id }}" @selected(old('terapis_id') == $t->id)>{{ $t->user->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-custom">Hari</label>
            <select name="hari" class="form-select-custom" required>
                @foreach ($hari as $h)
                    <option value="{{ $h }}" @selected(old('hari') === $h)>{{ $h }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-custom">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control-custom" value="{{ old('jam_mulai', '09:00') }}" required>
        </div>
        <div class="col-md-2">
            <label class="form-label-custom">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control-custom" value="{{ old('jam_selesai', '15:00') }}" required>
        </div>
        <div class="col-md-1">
            <label class="form-label-custom">Kuota</label>
            <input type="number" name="kuota_harian" min="1" class="form-control-custom" value="{{ old('kuota_harian', 6) }}" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-custom btn-custom-primary w-100"><i class="bi bi-plus-lg"></i> Tambah</button>
        </div>
    </form>
</div>

{{-- Daftar per terapis --}}
@forelse ($terapis as $t)
    <div class="card border-light shadow-sm p-4 mb-4">
        <h5 class="card-title mb-3">{{ $t->user->nama_lengkap }} <small class="text-muted fw-normal">{{ $t->spesialisasi }}</small></h5>
        <div class="table-responsive">
            <table class="table-custom">
                <thead><tr><th>Hari</th><th>Mulai</th><th>Selesai</th><th>Kuota Harian</th><th class="text-center">Aksi</th></tr></thead>
                <tbody>
                @forelse ($t->jadwalPraktik->sortBy(fn ($j) => array_search($j->hari, $hari)) as $j)
                    <tr>
                        <td>
                            <select name="hari" form="form-jadwal-{{ $j->id }}" class="form-select-custom form-control-custom-sm">
                                @foreach ($hari as $h)
                                    <option value="{{ $h }}" @selected($j->hari === $h)>{{ $h }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="time" name="jam_mulai" form="form-jadwal-{{ $j->id }}" class="form-control-custom form-control-custom-sm" value="{{ substr($j->jam_mulai, 0, 5) }}"></td>
                        <td><input type="time" name="jam_selesai" form="form-jadwal-{{ $j->id }}" class="form-control-custom form-control-custom-sm" value="{{ substr($j->jam_selesai, 0, 5) }}"></td>
                        <td><input type="number" name="kuota_harian" min="1" form="form-jadwal-{{ $j->id }}" class="form-control-custom form-control-custom-sm" value="{{ $j->kuota_harian }}"></td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button type="submit" form="form-jadwal-{{ $j->id }}" class="table-btn-action" title="Simpan"><i class="bi bi-check-lg"></i></button>
                                <button type="submit" form="hapus-jadwal-{{ $j->id }}" class="table-btn-action delete" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada jam praktik. Terapis ini tidak akan menerima pasien otomatis.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @foreach ($t->jadwalPraktik as $j)
            <form action="{{ route('admin.jadwal.update', $j) }}" method="POST" id="form-jadwal-{{ $j->id }}">
                @csrf @method('PUT')
                <input type="hidden" name="terapis_id" value="{{ $t->id }}">
            </form>
            <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" id="hapus-jadwal-{{ $j->id }}" data-confirm="Hapus jam praktik ini?">
                @csrf @method('DELETE')
            </form>
        @endforeach
    </div>
@empty
    <div class="alert-custom alert-custom-primary">
        <i class="bi bi-info-circle-fill alert-custom-icon"></i>
        <div class="alert-custom-content">Belum ada terapis aktif. Tambahkan terapis terlebih dahulu.</div>
    </div>
@endforelse
@endsection
