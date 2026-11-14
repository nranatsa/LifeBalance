<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pasien hanya MELIHAT riwayat pembayarannya.
 * Pembayaran dicatat dan dikelola sepenuhnya oleh admin (lihat Admin\PembayaranController).
 */
class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $pembayaran = Pembayaran::with('reservasi.paket')
            ->whereHas('reservasi', fn ($q) => $q->where('pasien_id', $request->user()->pasien->id))
            ->latest()
            ->paginate(10);

        return view('pasien.pembayaran.index', compact('pembayaran'));
    }
}
