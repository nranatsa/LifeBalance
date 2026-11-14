<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\PaketLayanan;
use App\Models\ReservasiAntrean;
use App\Services\PenjadwalanService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class ReservasiController extends Controller
{
    public function __construct(protected PenjadwalanService $penjadwalan) {}

    public function index(Request $request): View
    {
        $reservasi = $request->user()->pasien->reservasi()
            ->with(['paket', 'terapis.user', 'pembayaran'])
            ->orderByDesc('tanggal_praktik')
            ->orderByDesc('jam_mulai')
            ->paginate(10);

        return view('pasien.reservasi.index', compact('reservasi'));
    }

    /**
     * Tanpa ?paket= : tampilkan daftar paket yang dipublish.
     * Dengan ?paket=ID : tampilkan kalender; terapis muncul otomatis setelah tanggal dipilih.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $daftarPaket = PaketLayanan::published()->orderBy('harga')->get();

        $paket = null;

        if ($request->filled('paket')) {
            $paket = PaketLayanan::published()->find($request->integer('paket'));

            if (! $paket) {
                return redirect()->route('pasien.reservasi.create')->with('error', 'Paket layanan tidak tersedia.');
            }
        }

        return view('pasien.reservasi.create', [
            'daftarPaket' => $daftarPaket,
            'paket' => $paket,
            'hariAktif' => $this->penjadwalan->hariAktif(),
            'maxHari' => $this->penjadwalan->batasHari(),
        ]);
    }

    /**
     * Dipanggil kalender (AJAX) setiap kali pasien memilih tanggal.
     * Mengembalikan terapis & jam yang dipilihkan sistem, atau tersedia=false bila tidak ada.
     */
    public function slot(Request $request): JsonResponse
    {
        $data = $request->validate([
            'paket' => ['required', 'integer'],
            'tanggal' => ['required', 'date_format:Y-m-d'],
        ]);

        $paket = PaketLayanan::published()->find($data['paket']);
        if (! $paket) {
            return response()->json(['tersedia' => false, 'pesan' => 'Paket layanan tidak tersedia.'], 404);
        }

        $tanggal = Carbon::createFromFormat('Y-m-d', $data['tanggal'])->startOfDay();
        $slot = $this->penjadwalan->cariSlotPadaTanggal($paket, $request->user()->pasien, $tanggal);

        if (! $slot) {
            return response()->json([
                'tersedia' => false,
                'pesan' => 'Tidak ada terapis yang tersedia pada tanggal ini. Silakan pilih tanggal lain.',
            ]);
        }

        return response()->json([
            'tersedia' => true,
            'tanggal_label' => $tanggal->translatedFormat('l, d F Y'),
            'jam' => substr($slot['jam_mulai'], 0, 5).' - '.substr($slot['jam_selesai'], 0, 5),
            'terapis' => $slot['terapis']->user->nama_lengkap,
            'spesialisasi' => $slot['terapis']->spesialisasi,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'paket_layanan_id' => ['required', 'integer'],
            'tanggal' => ['required', 'date_format:Y-m-d'],
        ]);

        $paket = PaketLayanan::published()->find($data['paket_layanan_id']);

        if (! $paket) {
            return back()->with('error', 'Paket layanan tidak tersedia.');
        }

        $tanggal = Carbon::createFromFormat('Y-m-d', $data['tanggal'])->startOfDay();

        if (! $this->penjadwalan->tanggalDalamRentang($tanggal)) {
            return back()->withInput()->with('error', 'Tanggal harus antara hari ini dan '.$this->penjadwalan->batasHari().' hari ke depan.');
        }

        try {
            $reservasi = $this->penjadwalan->buatReservasi($request->user()->pasien, $paket, $tanggal);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('pasien.reservasi.show', $reservasi)
            ->with('success', 'Reservasi dibuat. Silakan lakukan pembayaran agar jadwal Anda terkonfirmasi.');
    }

    public function show(Request $request, ReservasiAntrean $reservasi): View
    {
        $this->pastikanMilikPasien($request, $reservasi);

        $reservasi->load(['paket', 'terapis.user', 'pembayaran']);

        return view('pasien.reservasi.show', compact('reservasi'));
    }

    /** Pembatalan oleh pasien hanya selama belum dibayar. */
    public function destroy(Request $request, ReservasiAntrean $reservasi): RedirectResponse
    {
        $this->pastikanMilikPasien($request, $reservasi);

        if ($reservasi->status_antrean !== 'menunggu_pembayaran') {
            return back()->with('error', 'Reservasi yang sudah dibayar hanya dapat dibatalkan melalui admin klinik.');
        }

        $reservasi->update(['status_antrean' => 'batal']);

        return redirect()->route('pasien.reservasi.index')->with('success', 'Reservasi berhasil dibatalkan.');
    }

    protected function pastikanMilikPasien(Request $request, ReservasiAntrean $reservasi): void
    {
        abort_unless($reservasi->pasien_id === $request->user()->pasien->id, 404);
    }
}
