@extends('layouts.spark')

@section('title', 'Tambah Terapis')
@section('page_title', 'Tambah Terapis')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form action="{{ route('admin.terapis.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.terapis._form')
    </form>
</div>
@endsection
