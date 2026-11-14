{{-- Layout template SPARK: dipakai oleh role ADMIN dan TERAPIS --}}
@php
    $authUser = auth()->user();
    $fotoSidebar = $authUser->isTerapis() ? $authUser->terapis?->foto_profil : null;
    $avatarUrl = $fotoSidebar ? asset('storage/'.$fotoSidebar) : asset('spark/assets/images/avatar.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.spark.head')
</head>
<body>

    @if ($authUser->isAdmin())
        @include('partials.spark.sidebar-admin')
    @else
        @include('partials.spark.sidebar-terapis')
    @endif

    <div class="main-wrapper">
        @include('partials.spark.navbar')

        @include('partials.spark.page-header')

        @include('partials.spark.alerts')

        @yield('content')
    </div>

    @include('partials.spark.scripts')
</body>
</html>
