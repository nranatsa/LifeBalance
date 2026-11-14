@php
    $kelas = match ($status) {
        'terjadwal', 'berlangsung', 'selesai', 'lunas' => 'success',
        'batal', 'ditolak' => 'failed',
        default => 'pending',
    };
    $label = \App\Models\ReservasiAntrean::STATUS[$status] ?? ucfirst($status);
@endphp
<span class="badge-table {{ $kelas }}">{{ $label }}</span>
