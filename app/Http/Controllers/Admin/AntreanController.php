<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservasiAntrean;
use App\Models\Terapis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AntreanController extends Controller
{
    /**
     * Status sekarang => status yang boleh dituju.
     * Admin-lah yang menentukan sesi dimulai dan selesai; terapis hanya melihat jadwal.
     */
    protected const TRANSISI = [
        'menunggu_pembayaran' => ['terjadwal', 'batal'], // status lama (sebelum pembayaran dipindah ke akhir sesi)
        'terjadwal' => ['berlangsung', 'batal'],
        'berlangsung' => ['selesai'],
        'selesai' => [],
        'batal' => [],
    ];

    public function index(Request $request): View
    {
        // tanpa parameter => hari ini; parameter kosong / 'semua' => semua tanggal
        $tanggal = $request->has('tanggal')
            ? ($request->input('tanggal') ?: 'semua')
            : today()->toDateString();

        $antrean = ReservasiAntrean::with(['pasien.user', 'paket', 'terapis.user', 'pembayaran'])
            ->when($tanggal !== 'semua', fn ($q) => $q->whereDate('tanggal_praktik', $tanggal))
            ->when($request->filled('status'), fn ($q) => $q->where('status_antrean', $request->string('status')->toString()))
            ->when($request->filled('terapis_id'), fn ($q) => $q->where('terapis_id', $request->integer('terapis_id')))
            ->orderBy('tanggal_praktik')
            ->orderBy('jam_mulai')
            ->paginate(15)
            ->withQueryString();

        $daftarTerapis = Terapis::with('user')->get();

        return view('admin.antrean.index', compact('antrean', 'daftarTerapis', 'tanggal'));
    }

    public function show(ReservasiAntrean $antrean): View
    {
        $antrean->load(['pasien.user', 'paket', 'terapis.user', 'pembayaran']);

        return view('admin.antrean.show', ['reservasi' => $antrean, 'transisi' => self::TRANSISI[$antrean->status_antrean]]);
    }

    public function updateStatus(Request $request, ReservasiAntrean $antrean): RedirectResponse
    {
        $data = $request->validate([
            'status_antrean' => ['required', 'in:'.implode(',', array_keys(ReservasiAntrean::STATUS))],
        ]);

        if (! in_array($data['status_antrean'], self::TRANSISI[$antrean->status_antrean], true)) {
            return back()->with('error', 'Perubahan status tersebut tidak diizinkan.');
        }

        $antrean->update(['status_antrean' => $data['status_antrean']]);

        return back()->with('success', 'Status antrean diperbarui menjadi "'.$antrean->status_label.'".');
    }
}
