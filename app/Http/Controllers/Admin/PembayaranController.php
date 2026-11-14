<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\ReservasiAntrean;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Seluruh pembayaran dikelola admin. Pasien membayar langsung ke klinik SETELAH sesi selesai,
 * lalu admin mencatatnya di sini. Pencatatan pembayaran tidak mengubah status antrean.
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

        $menungguBayar = ReservasiAntrean::belumDibayar()->count();

        return view('admin.pembayaran.index', compact('pembayaran', 'menungguBayar'));
    }

    public function create(Request $request): View
    {
        $menunggu = ReservasiAntrean::with(['pasien.user', 'paket'])
            ->belumDibayar()
            ->orderBy('tanggal_praktik')
            ->orderBy('jam_mulai')
            ->get();

        $terpilih = $menunggu->firstWhere('id', $request->integer('reservasi'));

        return view('admin.pembayaran.create', compact('menunggu', 'terpilih'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reservasi_id' => ['required', 'integer', 'exists:reservasi_antrean,id'],
            'metode_pembayaran' => ['required', 'in:transfer,tunai,qris'],
            'total_bayar' => ['required', 'numeric', 'min:0'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $pembayaran = DB::transaction(function () use ($request, $data) {
            $reservasi = ReservasiAntrean::whereKey($data['reservasi_id'])->lockForUpdate()->first();

            // pembayaran hanya untuk sesi yang sudah selesai dan belum lunas
            if ($reservasi->status_antrean !== 'selesai' || $reservasi->pembayaranLunas()->exists()) {
                return null;
            }

            return Pembayaran::create([
                'reservasi_id' => $reservasi->id,
                'total_bayar' => $data['total_bayar'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'bukti_pembayaran' => $request->hasFile('bukti_pembayaran')
                    ? $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public')
                    : null,
                'status_pembayaran' => 'lunas',
                'diverifikasi_oleh' => $request->user()->id,
                'tanggal_bayar' => now(),
            ]);
        });

        if (! $pembayaran) {
            return back()->withInput()->with('error', 'Pembayaran hanya bisa dicatat untuk sesi yang sudah selesai dan belum dibayar.');
        }

        return redirect()->route('admin.pembayaran.show', $pembayaran)
            ->with('success', 'Pembayaran dicatat sebagai lunas.');
    }

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load(['reservasi.pasien.user', 'reservasi.paket', 'reservasi.terapis.user', 'verifikator']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /** Membatalkan pembayaran yang salah catat. Reservasi kembali tercatat belum dibayar. */
    public function batalkan(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        if ($pembayaran->status_pembayaran !== 'lunas') {
            return back()->with('error', 'Pembayaran ini sudah dibatalkan.');
        }

        $pembayaran->update([
            'status_pembayaran' => 'ditolak',
            'diverifikasi_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Pembayaran dibatalkan. Sesi ini kembali tercatat belum dibayar.');
    }
}
