<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikTerapis;
use App\Models\ReservasiAntrean;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Jadwal dibuat otomatis oleh sistem; terapis tidak bisa mengubah jadwal.
 * Hanya reservasi yang sudah dibayar (terjadwal / berlangsung / selesai) yang ditampilkan.
 * Terapis-lah yang mengonfirmasi sesi dimulai dan selesai.
 */
class JadwalSayaController extends Controller
{
    protected const STATUS_TAMPIL = ['terjadwal', 'berlangsung', 'selesai'];

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

    /** terjadwal -> berlangsung */
    public function mulai(Request $request, ReservasiAntrean $reservasi): RedirectResponse
    {
        $this->pastikanMilikTerapis($request, $reservasi);

        if ($reservasi->status_antrean !== 'terjadwal') {
            return back()->with('error', 'Hanya sesi berstatus Terjadwal yang bisa dimulai.');
        }

        if ($pesan = $this->belumWaktunya($reservasi)) {
            return back()->with('error', $pesan);
        }

        $reservasi->update(['status_antrean' => 'berlangsung']);

        return back()->with('success', 'Sesi dimulai.');
    }

    /** terjadwal / berlangsung -> selesai */
    public function selesai(Request $request, ReservasiAntrean $reservasi): RedirectResponse
    {
        $this->pastikanMilikTerapis($request, $reservasi);

        if (! in_array($reservasi->status_antrean, ['terjadwal', 'berlangsung'], true)) {
            return back()->with('error', 'Sesi ini tidak bisa ditandai selesai.');
        }

        if ($pesan = $this->belumWaktunya($reservasi)) {
            return back()->with('error', $pesan);
        }

        $reservasi->update(['status_antrean' => 'selesai']);

        return back()->with('success', 'Sesi ditandai selesai.');
    }

    protected function pastikanMilikTerapis(Request $request, ReservasiAntrean $reservasi): void
    {
        abort_unless($reservasi->terapis_id === $request->user()->terapis->id, 404);
    }

    /** Sesi tanggal mendatang belum boleh dimulai/diselesaikan. */
    protected function belumWaktunya(ReservasiAntrean $reservasi): ?string
    {
        return $reservasi->tanggal_praktik->isFuture()
            ? 'Sesi ini dijadwalkan pada '.$reservasi->tanggal_praktik->translatedFormat('d F Y').' dan belum bisa diubah statusnya.'
            : null;
    }
}
