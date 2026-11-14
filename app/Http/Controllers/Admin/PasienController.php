<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(Request $request): View
    {
        $pasien = Pasien::with('user')
            ->withCount('reservasi')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q')->toString();
                $query->whereHas('user', fn ($u) => $u
                    ->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_telepon', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pasien.index', compact('pasien'));
    }

    public function show(Pasien $pasien): View
    {
        $pasien->load('user');

        $reservasi = $pasien->reservasi()
            ->with(['paket', 'terapis.user', 'pembayaran'])
            ->orderByDesc('tanggal_praktik')
            ->orderByDesc('jam_mulai')
            ->paginate(10);

        return view('admin.pasien.show', compact('pasien', 'reservasi'));
    }
}
