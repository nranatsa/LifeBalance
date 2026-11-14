<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\PaketLayanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $pasien = $request->user()->pasien;

        $paket = PaketLayanan::published()->orderBy('harga')->get();

        $reservasiMendatang = $pasien->reservasi()
            ->with(['paket', 'terapis.user', 'pembayaran'])
            ->aktif()
            ->where('status_antrean', '!=', 'selesai')
            ->where('tanggal_praktik', '>=', today())
            ->orderBy('tanggal_praktik')
            ->orderBy('jam_mulai')
            ->limit(3)
            ->get();

        return view('pasien.dashboard', compact('paket', 'reservasiMendatang'));
    }
}
