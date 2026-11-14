@extends('layouts.spark')

@section('title', $paket->nama_paket)
@section('page_title', $paket->nama_paket)
@section('page_actions')
    <a href="{{ route('admin.paket.edit', $paket) }}" class="btn-custom btn-custom-primary"><i class="bi bi-pencil"></i> Ubah</a>
@endsection

@section('content')
<div class="card border-light shadow-sm p-4">
    <div class="row g-4">
        @if ($paket->foto_paket)
            <div class="col-md-4"><img src="{{ asset('storage/'.$paket->foto_paket) }}" class="img-fluid rounded" alt=""></div>
        @endif
        <div class="col">
            <p>{{ $paket->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
            <dl class="row mb-0">
                <dt class="col-sm-3">Durasi</dt><dd class="col-sm-9">{{ $paket->durasi_menit }} menit</dd>
                <dt class="col-sm-3">Harga</dt><dd class="col-sm-9">Rp {{ number_format($paket->harga, 0, ',', '.') }}</dd>
                <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $paket->status_publish ? 'Publish' : 'Draft' }}</dd>
                <dt class="col-sm-3">Total Reservasi</dt><dd class="col-sm-9">{{ $paket->reservasi_count }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
