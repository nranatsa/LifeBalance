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
            'belum_dibayar' => ReservasiAntrean::belumDibayar()->count(),
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

        $menungguBayar = ReservasiAntrean::with(['pasien.user', 'paket'])
            ->belumDibayar()
            ->orderBy('tanggal_praktik')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stat', 'antreanHariIni', 'menungguBayar'));
    }
}
