<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikTerapis;
use App\Models\Terapis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin mengatur jam praktik mingguan tiap terapis.
 * Jadwal inilah yang dipakai PenjadwalanService untuk menempatkan pasien secara otomatis.
 */
class JadwalPraktikController extends Controller
{
    public function index(Request $request): View
    {
        $terapis = Terapis::with(['user', 'jadwalPraktik' => fn ($q) => $q->orderBy('jam_mulai')])
            ->aktif()
            ->get();

        $hari = JadwalPraktikTerapis::HARI;

        return view('admin.jadwal.index', compact('terapis', 'hari'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($this->bentrok($data)) {
            return back()->withInput()->with('error', 'Jam praktik bentrok dengan jadwal lain milik terapis ini di hari yang sama.');
        }

        JadwalPraktikTerapis::create($data);

        return back()->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalPraktikTerapis $jadwal): RedirectResponse
    {
        $data = $this->validated($request);

        if ($this->bentrok($data, $jadwal->id)) {
            return back()->withInput()->with('error', 'Jam praktik bentrok dengan jadwal lain milik terapis ini di hari yang sama.');
        }

        $jadwal->update($data);

        return back()->with('success', 'Jadwal praktik berhasil diperbarui.');
    }

    public function destroy(JadwalPraktikTerapis $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal praktik berhasil dihapus. Reservasi yang sudah ada tidak berubah.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'terapis_id' => ['required', 'exists:terapis,id'],
            'hari' => ['required', 'in:'.implode(',', JadwalPraktikTerapis::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'kuota_harian' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        // samakan format dengan kolom TIME di database (H:i:s) agar perbandingan string di SQL akurat
        $data['jam_mulai'] .= ':00';
        $data['jam_selesai'] .= ':00';

        return $data;
    }

    protected function bentrok(array $data, ?int $abaikanId = null): bool
    {
        return JadwalPraktikTerapis::where('terapis_id', $data['terapis_id'])
            ->where('hari', $data['hari'])
            ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();
    }
}
