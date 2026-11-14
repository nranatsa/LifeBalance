<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
<link rel="icon" type="image/png" href="{{ asset('spark/assets/images/favicon.ico') }}">

<link rel="stylesheet" href="{{ asset('spark/assets/libs/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('spark/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('spark/assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('spark/assets/css/main.css') }}">

@stack('styles')
