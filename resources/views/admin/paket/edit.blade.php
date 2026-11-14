@extends('layouts.spark')

@section('title', 'Ubah Paket')
@section('page_title', 'Ubah Paket Layanan')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form action="{{ route('admin.paket.update', $paket) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.paket._form', ['paket' => $paket])
    </form>
</div>
@endsection
