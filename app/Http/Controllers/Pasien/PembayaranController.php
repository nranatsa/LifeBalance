<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\ReservasiAntrean;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function create(Request $request, ReservasiAntrean $reservasi): View|RedirectResponse
    {
        $this->pastikanMilikPasien($request, $reservasi);

        if ($pesan = $this->alasanTidakBisaBayar($reservasi)) {
            return redirect()->route('pasien.reservasi.show', $reservasi)->with('error', $pesan);
        }

        $reservasi->load('paket');

        return view('pasien.pembayaran.create', compact('reservasi'));
    }

    public function store(Request $request, ReservasiAntrean $reservasi): RedirectResponse
    {
        $this->pastikanMilikPasien($request, $reservasi);

        if ($pesan = $this->alasanTidakBisaBayar($reservasi)) {
            return redirect()->route('pasien.reservasi.show', $reservasi)->with('error', $pesan);
        }

        $data = $request->validate([
            'metode_pembayaran' => ['required', 'in:transfer,tunai,qris'],
            'bukti_pembayaran' => ['required_unless:metode_pembayaran,tunai', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = $request->hasFile('bukti_pembayaran')
            ? $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public')
            : null;

        Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'total_bayar' => $reservasi->paket->harga,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('pasien.reservasi.show', $reservasi)
            ->with('success', 'Pembayaran dikirim dan menunggu verifikasi admin.');
    }

    protected function alasanTidakBisaBayar(ReservasiAntrean $reservasi): ?string
    {
        if ($reservasi->status_antrean !== 'menunggu_pembayaran') {
            return 'Reservasi ini tidak memerlukan pembayaran.';
        }

        $terakhir = $reservasi->pembayaran;

        if ($terakhir && $terakhir->status_pembayaran === 'pending') {
            return 'Pembayaran Anda sedang menunggu verifikasi admin.';
        }

        return null;
    }

    protected function pastikanMilikPasien(Request $request, ReservasiAntrean $reservasi): void
    {
        abort_unless($reservasi->pasien_id === $request->user()->pasien->id, 404);
    }
}
