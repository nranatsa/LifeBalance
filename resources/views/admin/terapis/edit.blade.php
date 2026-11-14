@extends('layouts.spark')

@section('title', 'Ubah Terapis')
@section('page_title', 'Ubah Terapis')

@section('content')
<div class="card border-light shadow-sm p-4">
    <form action="{{ route('admin.terapis.update', $terapis) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.terapis._form', ['terapis' => $terapis])
    </form>
</div>
@endsection
