@extends('layouts.spark')

@section('title', 'Detail Pasien')
@section('page_title', 'Sesi #'.$reservasi->nomor_antrean)
@section('page_subtitle', $reservasi->tanggal_praktik->translatedFormat('l, d F Y').' - '.$reservasi->jam)
@section('page_actions')
    <a href="{{ route('terapis.jadwal.index') }}" class="btn-custom btn-custom-light"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection

@section('content')
@php($pasien = $reservasi->pasien)
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Pasien</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Nama</dt><dd class="col-sm-8">{{ $pasien?->user?->nama_lengkap ?? '-' }}</dd>
                <dt class="col-sm-4">Umur</dt><dd class="col-sm-8">{{ $pasien?->umur ?? '-' }} tahun</dd>
                <dt class="col-sm-4">Jenis Kelamin</dt><dd class="col-sm-8">{{ ($pasien?->jenis_kelamin) === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                <dt class="col-sm-4">Catatan Medis</dt><dd class="col-sm-8">{{ $pasien?->catatan_medis ?: '-' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-light shadow-sm p-4 h-100">
            <h5 class="card-title mb-3">Layanan</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Paket</dt><dd class="col-sm-8">{{ $reservasi->paket?->nama_paket ?? '-' }}</dd>
                <dt class="col-sm-4">Durasi</dt><dd class="col-sm-8">{{ $reservasi->paket?->durasi_menit ?? '-' }} menit</dd>
                <dt class="col-sm-4">Deskripsi</dt><dd class="col-sm-8">{{ $reservasi->paket?->deskripsi ?: '-' }}</dd>
                <dt class="col-sm-4">Status</dt><dd class="col-sm-8">@include('partials.spark.status-badge', ['status' => $reservasi->status_antrean])</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
