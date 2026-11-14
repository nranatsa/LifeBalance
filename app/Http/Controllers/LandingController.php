<?php

namespace App\Http\Controllers;

use App\Models\PaketLayanan;
use App\Models\Pasien;
use App\Models\ReservasiAntrean;
use App\Models\Terapis;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Halaman publik sebelum login: paket layanan & daftar terapis. */
class LandingController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect(auth()->user()->dashboardRoute());
        }

        $paket = PaketLayanan::published()->orderBy('harga')->get();

        $terapis = Terapis::with('user')->aktif()->get();

        $stat = [
            'terapis' => $terapis->count(),
            'paket' => $paket->count(),
            'pasien' => Pasien::count(),
            'sesi_selesai' => ReservasiAntrean::where('status_antrean', 'selesai')->count(),
        ];

        return view('landing', compact('paket', 'terapis', 'stat'));
    }
}
