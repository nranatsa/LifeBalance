<?php

namespace Database\Seeders;

use App\Models\JadwalPraktikTerapis;
use App\Models\PaketLayanan;
use App\Models\Pasien;
use App\Models\Terapis;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Semua akun demo memakai password: password
        User::create([
            'nama_lengkap' => 'Admin Life Balance',
            'email' => 'admin@lifebalance.test',
            'no_telepon' => '081200000001',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $daftarTerapis = [
            ['Dewi Lestari, S.Tr.Kes', 'terapis1@lifebalance.test', '081200000011', 'Fisioterapi Muskuloskeletal'],
            ['Budi Santoso, S.Tr.Kes', 'terapis2@lifebalance.test', '081200000012', 'Terapi Okupasi'],
        ];

        foreach ($daftarTerapis as [$nama, $email, $telp, $spesialis]) {
            $user = User::create([
                'nama_lengkap' => $nama,
                'email' => $email,
                'no_telepon' => $telp,
                'password' => 'password',
                'role' => 'terapis',
            ]);

            $terapis = Terapis::create(['user_id' => $user->id, 'spesialisasi' => $spesialis, 'status_aktif' => true]);

            foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari) {
                JadwalPraktikTerapis::create([
                    'terapis_id' => $terapis->id,
                    'hari' => $hari,
                    'jam_mulai' => '09:00',
                    'jam_selesai' => '15:00',
                    'kuota_harian' => 6,
                ]);
            }
        }

        $paket = [
            ['Konsultasi & Asesmen Awal', 'Pemeriksaan awal kondisi pasien dan penyusunan rencana terapi.', 30, 100000],
            ['Terapi Reguler', 'Sesi terapi satu kali sesuai rencana yang telah disusun terapis.', 60, 200000],
            ['Terapi Intensif', 'Sesi terapi panjang untuk kondisi yang membutuhkan penanganan lebih lama.', 90, 300000],
        ];

        foreach ($paket as [$nama, $deskripsi, $durasi, $harga]) {
            PaketLayanan::create([
                'nama_paket' => $nama,
                'deskripsi' => $deskripsi,
                'durasi_menit' => $durasi,
                'harga' => $harga,
                'status_publish' => true,
            ]);
        }

        $pasien = User::create([
            'nama_lengkap' => 'Pasien Contoh',
            'email' => 'pasien@lifebalance.test',
            'no_telepon' => '081200000021',
            'password' => 'password',
            'role' => 'pasien',
        ]);

        Pasien::create([
            'user_id' => $pasien->id,
            'tanggal_lahir' => '1995-05-17',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Contoh No. 1, Pekanbaru',
        ]);
    }
}
