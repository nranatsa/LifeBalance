@php($label = ['ditolak' => 'Dibatalkan'][$status] ?? \App\Models\ReservasiAntrean::STATUS[$status] ?? ucfirst($status))
<span class="hw-badge {{ $status }}">{{ $label }}</span>
