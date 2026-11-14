<?php

namespace App\Services;

use App\Models\JadwalPraktikTerapis;
use App\Models\PaketLayanan;
use App\Models\Pasien;
use App\Models\ReservasiAntrean;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Penjadwalan otomatis berdasarkan TANGGAL yang dipilih pasien.
 *
 * Untuk tanggal tertentu, sistem memilih jam & terapis dengan aturan:
 *  1. Terapis harus aktif dan punya jam praktik pada hari tersebut (jadwal_praktik_terapis).
 *  2. Slot harus muat di jam praktik sesuai durasi paket.
 *  3. Tidak boleh bentrok dengan reservasi aktif terapis tersebut maupun reservasi pasien itu sendiri.
 *  4. Kuota harian per jam praktik tidak boleh terlampaui.
 *  5. Dipilih jam paling awal; bila beberapa terapis sama-sama bisa, dipilih yang bebannya paling sedikit.
 *  6. Untuk hari ini, slot baru boleh dimulai minimal $jedaMenit menit dari sekarang.
 *
 * Bila tidak ada terapis yang bisa, hasilnya null dan reservasi tidak boleh dibuat.
 */
class PenjadwalanService
{
    public function __construct(
        protected int $maxHari = 30,
        protected int $jedaMenit = 60,
    ) {}

    public function batasHari(): int
    {
        return $this->maxHari;
    }

    /**
     * Nomor hari ISO (1=Senin ... 7=Minggu) yang punya setidaknya satu terapis aktif berpraktik.
     * Dipakai kalender pasien untuk menonaktifkan hari yang pasti kosong.
     *
     * @return int[]
     */
    public function hariAktif(): array
    {
        $nama = JadwalPraktikTerapis::whereHas('terapis', fn ($q) => $q->where('status_aktif', true))
            ->distinct()
            ->pluck('hari');

        return $nama
            ->map(fn ($h) => array_search($h, JadwalPraktikTerapis::HARI) + 1)
            ->sort()
            ->values()
            ->all();
    }

    public function tanggalDalamRentang(Carbon $tanggal): bool
    {
        return $tanggal->betweenIncluded(today(), today()->addDays($this->maxHari));
    }

    /**
     * Mencari jam & terapis untuk satu tanggal. Tidak menyimpan apa pun.
     *
     * @return array{tanggal: Carbon, jam_mulai: string, jam_selesai: string, terapis_id: int, terapis: \App\Models\Terapis}|null
     */
    public function cariSlotPadaTanggal(PaketLayanan $paket, ?Pasien $pasien, Carbon $tanggal, bool $kunci = false): ?array
    {
        $tanggal = $tanggal->copy()->startOfDay();

        if (! $this->tanggalDalamRentang($tanggal)) {
            return null;
        }

        $durasi = (int) $paket->durasi_menit;
        $sekarang = now();
        $hari = JadwalPraktikTerapis::HARI[$tanggal->dayOfWeekIso - 1];

        $jadwalHariIni = JadwalPraktikTerapis::with('terapis.user')
            ->where('hari', $hari)
            ->whereHas('terapis', fn ($q) => $q->where('status_aktif', true))
            ->orderBy('jam_mulai')
            ->get();

        if ($jadwalHariIni->isEmpty()) {
            return null;
        }

        $milikPasien = $pasien
            ? $this->reservasiAktif($tanggal, 'pasien_id', $pasien->id, $kunci)
            : collect();

        $kandidat = [];

        foreach ($jadwalHariIni as $jadwal) {
            $milikTerapis = $this->reservasiAktif($tanggal, 'terapis_id', $jadwal->terapis_id, $kunci);

            $awal = $this->menit($jadwal->jam_mulai);
            $akhir = $this->menit($jadwal->jam_selesai);

            // kuota harian per jam praktik
            $terpakai = $milikTerapis->filter(function ($r) use ($awal, $akhir) {
                $m = $this->menit($r->jam_mulai);

                return $m >= $awal && $m < $akhir;
            })->count();

            if ($terpakai >= $jadwal->kuota_harian) {
                continue;
            }

            // hari ini: jangan buat slot yang sudah lewat / terlalu mepet
            $kursor = $awal;
            if ($tanggal->isToday()) {
                $batas = $sekarang->hour * 60 + $sekarang->minute + $this->jedaMenit;
                $kursor = max($kursor, (int) (ceil($batas / 5) * 5));
            }

            $sibuk = $milikTerapis->merge($milikPasien)
                ->map(fn ($r) => [$this->menit($r->jam_mulai), $this->menit($r->jam_selesai)])
                ->sortBy(0)
                ->values();

            while ($kursor + $durasi <= $akhir) {
                $bentrok = $sibuk->first(fn ($b) => $b[0] < $kursor + $durasi && $b[1] > $kursor);

                if ($bentrok) {
                    $kursor = max($kursor + 1, $bentrok[1]);

                    continue;
                }

                $kandidat[] = [
                    'tanggal' => $tanggal,
                    'jam_mulai' => $this->format($kursor),
                    'jam_selesai' => $this->format($kursor + $durasi),
                    'terapis_id' => $jadwal->terapis_id,
                    'terapis' => $jadwal->terapis,
                    'beban' => $milikTerapis->count(),
                    'urut' => $kursor,
                ];
                break;
            }
        }

        if (! $kandidat) {
            return null;
        }

        usort($kandidat, fn ($a, $b) => [$a['urut'], $a['beban'], $a['terapis_id']] <=> [$b['urut'], $b['beban'], $b['terapis_id']]);

        return $kandidat[0];
    }

    /**
     * Membuat reservasi + nomor antrean secara atomik untuk tanggal pilihan pasien.
     *
     * @throws RuntimeException bila tidak ada terapis yang bisa melayani pada tanggal itu
     */
    public function buatReservasi(Pasien $pasien, PaketLayanan $paket, Carbon $tanggal): ReservasiAntrean
    {
        return DB::transaction(function () use ($pasien, $paket, $tanggal) {
            // dihitung ulang di dalam transaksi: slot yang tadi tampil bisa saja sudah diambil pasien lain
            $slot = $this->cariSlotPadaTanggal($paket, $pasien, $tanggal, kunci: true);

            if (! $slot) {
                throw new RuntimeException(
                    'Tidak ada terapis yang tersedia pada '.$tanggal->translatedFormat('l, d F Y').'. Silakan pilih tanggal lain.'
                );
            }

            $nomor = (int) ReservasiAntrean::where('terapis_id', $slot['terapis_id'])
                ->whereDate('tanggal_praktik', $slot['tanggal'])
                ->max('nomor_antrean');

            return ReservasiAntrean::create([
                'kode_booking' => ReservasiAntrean::generateKode(),
                'pasien_id' => $pasien->id,
                'paket_layanan_id' => $paket->id,
                'terapis_id' => $slot['terapis_id'],
                'tanggal_praktik' => $slot['tanggal']->toDateString(),
                'jam_mulai' => $slot['jam_mulai'],
                'jam_selesai' => $slot['jam_selesai'],
                'nomor_antrean' => $nomor + 1,
                'status_antrean' => 'menunggu_pembayaran',
            ]);
        });
    }

    protected function reservasiAktif(Carbon $tanggal, string $kolom, int $id, bool $kunci): Collection
    {
        $query = ReservasiAntrean::where($kolom, $id)
            ->whereDate('tanggal_praktik', $tanggal->toDateString())
            ->where('status_antrean', '!=', 'batal');

        if ($kunci) {
            $query->lockForUpdate();
        }

        return $query->get(['id', 'terapis_id', 'pasien_id', 'jam_mulai', 'jam_selesai']);
    }

    protected function menit(string $jam): int
    {
        [$h, $m] = array_map('intval', explode(':', $jam));

        return $h * 60 + $m;
    }

    protected function format(int $menit): string
    {
        return sprintf('%02d:%02d:00', intdiv($menit, 60), $menit % 60);
    }
}
