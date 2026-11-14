<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Pembayaran hanya melibatkan pasien dan admin. Terapis tidak punya akses.
 */
class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $pembayaran = Pembayaran::with(['reservasi.pasien.user', 'reservasi.paket'])
            ->when($request->filled('status'), fn ($q) => $q->where('status_pembayaran', $request->string('status')->toString()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load(['reservasi.pasien.user', 'reservasi.paket', 'reservasi.terapis.user', 'verifikator']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        if ($pembayaran->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        DB::transaction(function () use ($request, $pembayaran) {
            $pembayaran->update([
                'status_pembayaran' => 'lunas',
                'diverifikasi_oleh' => $request->user()->id,
                'tanggal_bayar' => now(),
            ]);

            $reservasi = $pembayaran->reservasi;
            if ($reservasi->status_antrean === 'menunggu_pembayaran') {
                $reservasi->update(['status_antrean' => 'terjadwal']);
            }
        });

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran diverifikasi. Reservasi pasien kini berstatus terjadwal.');
    }

    public function tolak(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        if ($pembayaran->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        $pembayaran->update([
            'status_pembayaran' => 'ditolak',
            'diverifikasi_oleh' => $request->user()->id,
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran ditolak. Pasien dapat mengunggah bukti pembayaran kembali.');
    }
}
