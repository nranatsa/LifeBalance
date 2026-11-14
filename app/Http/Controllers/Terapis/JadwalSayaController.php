<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikTerapis;
use App\Models\ReservasiAntrean;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Terapis hanya MELIHAT jadwal yang dibuat otomatis oleh sistem.
 * Status sesi (berlangsung / selesai) ditentukan oleh admin.
 * Jadwal tampil sejak reservasi dibuat, tanpa menunggu pembayaran (pembayaran dilakukan setelah sesi selesai).
 */
class JadwalSayaController extends Controller
{
    protected const STATUS_TAMPIL = ['menunggu_pembayaran', 'terjadwal', 'berlangsung', 'selesai'];

    public function index(Request $request): View
    {
        $terapis = $request->user()->terapis;

        $tanggal = $request->input('tanggal');

        $reservasi = ReservasiAntrean::with(['pasien.user', 'paket'])
            ->where('terapis_id', $terapis->id)
            ->whereIn('status_antrean', self::STATUS_TAMPIL)
            ->when(
                $tanggal,
                fn ($q) => $q->whereDate('tanggal_praktik', $tanggal),
                fn ($q) => $q->whereDate('tanggal_praktik', '>=', today())
            )
            ->orderBy('tanggal_praktik')
            ->orderBy('jam_mulai')
            ->paginate(15)
            ->withQueryString();

        $jamPraktik = $terapis->jadwalPraktik()->orderBy('jam_mulai')->get()
            ->sortBy(fn ($j) => array_search($j->hari, JadwalPraktikTerapis::HARI))
            ->groupBy('hari');

        return view('terapis.jadwal.index', compact('reservasi', 'jamPraktik', 'tanggal'));
    }

    public function show(Request $request, ReservasiAntrean $reservasi): View
    {
        abort_unless(
            $reservasi->terapis_id === $request->user()->terapis->id
                && in_array($reservasi->status_antrean, self::STATUS_TAMPIL, true),
            404
        );

        $reservasi->load(['pasien.user', 'paket']);

        return view('terapis.jadwal.show', compact('reservasi'));
    }
}
