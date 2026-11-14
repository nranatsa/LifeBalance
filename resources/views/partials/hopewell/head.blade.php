<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Beranda') - {{ config('app.name') }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<link href="{{ asset('hopewell/assets/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="{{ asset('hopewell/assets/style.css') }}" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('hopewell/assets/hand-heart.png') }}">

<style>
    /* penyesuaian kecil untuk halaman aplikasi (bukan landing page) */
    .hw-panel{background:#fff;border-radius:18px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:1.5rem}
    .hw-badge{display:inline-block;padding:.25rem .7rem;border-radius:999px;font-size:.78rem;font-weight:600}
    .hw-badge.menunggu_pembayaran,.hw-badge.pending{background:#fef3c7;color:#92400e}
    .hw-badge.terjadwal,.hw-badge.lunas{background:#dcfce7;color:#166534}
    .hw-badge.berlangsung{background:#dbeafe;color:#1e40af}
    .hw-badge.selesai{background:#e2e8f0;color:#334155}
    .hw-badge.batal,.hw-badge.ditolak{background:#fee2e2;color:#991b1b}
    .page-banner{padding:2.2rem 0 1rem}
</style>

@stack('styles')
