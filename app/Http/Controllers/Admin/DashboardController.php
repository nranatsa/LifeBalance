<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketLayanan;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\ReservasiAntrean;
use App\Models\Terapis;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hariIni = today()->toDateString();

        $stat = [
            'pasien' => Pasien::count(),
            'terapis' => Terapis::aktif()->count(),
            'paket' => PaketLayanan::published()->count(),
            'antrean_hari_ini' => ReservasiAntrean::aktif()->whereDate('tanggal_praktik', $hariIni)->count(),
            'pembayaran_pending' => Pembayaran::where('status_pembayaran', 'pending')->count(),
            'pendapatan_bulan_ini' => Pembayaran::where('status_pembayaran', 'lunas')
                ->whereYear('tanggal_bayar', now()->year)
                ->whereMonth('tanggal_bayar', now()->month)
                ->sum('total_bayar'),
        ];

        $antreanHariIni = ReservasiAntrean::with(['pasien.user', 'paket', 'terapis.user'])
            ->aktif()
            ->whereDate('tanggal_praktik', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        $pembayaranPending = Pembayaran::with('reservasi.pasien.user', 'reservasi.paket')
            ->where('status_pembayaran', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stat', 'antreanHariIni', 'pembayaranPending'));
    }
}
