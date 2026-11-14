@extends('layouts.spark')

@section('title', 'Tambah Paket')
@section('page_title', 'Tambah Paket Layanan')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.paket._form')
    </form>
</div>
@endsection
