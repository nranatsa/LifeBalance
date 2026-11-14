<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Pasien;
use App\Http\Controllers\Terapis;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

/* ---------------------------------- Auth ---------------------------------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* ---------------------------------- Admin --------------------------------- */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('paket', Admin\PaketLayananController::class);
    Route::resource('terapis', Admin\TerapisController::class)->parameters(['terapis' => 'terapi']);

    Route::get('jadwal-praktik', [Admin\JadwalPraktikController::class, 'index'])->name('jadwal.index');
    Route::post('jadwal-praktik', [Admin\JadwalPraktikController::class, 'store'])->name('jadwal.store');
    Route::put('jadwal-praktik/{jadwal}', [Admin\JadwalPraktikController::class, 'update'])->name('jadwal.update');
    Route::delete('jadwal-praktik/{jadwal}', [Admin\JadwalPraktikController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('pasien', [Admin\PasienController::class, 'index'])->name('pasien.index');
    Route::get('pasien/{pasien}', [Admin\PasienController::class, 'show'])->name('pasien.show');

    Route::get('pembayaran', [Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/create', [Admin\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran', [Admin\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('pembayaran/{pembayaran}', [Admin\PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::patch('pembayaran/{pembayaran}/batalkan', [Admin\PembayaranController::class, 'batalkan'])->name('pembayaran.batalkan');

    Route::get('antrean', [Admin\AntreanController::class, 'index'])->name('antrean.index');
    Route::get('antrean/{antrean}', [Admin\AntreanController::class, 'show'])->name('antrean.show');
    Route::patch('antrean/{antrean}/status', [Admin\AntreanController::class, 'updateStatus'])->name('antrean.status');
});

/* ---------------------------------- Pasien -------------------------------- */
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/', [Pasien\DashboardController::class, 'index'])->name('dashboard');

    Route::get('profil', [Pasien\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Pasien\ProfilController::class, 'update'])->name('profil.update');

    Route::get('reservasi/slot', [Pasien\ReservasiController::class, 'slot'])->name('reservasi.slot');
    Route::resource('reservasi', Pasien\ReservasiController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::get('pembayaran', [Pasien\PembayaranController::class, 'index'])->name('pembayaran.index');
});

/* --------------------------------- Terapis -------------------------------- */
Route::middleware(['auth', 'role:terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::get('jadwal-saya', [Terapis\JadwalSayaController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal-saya/{reservasi}', [Terapis\JadwalSayaController::class, 'show'])->name('jadwal.show');

    Route::get('profil', [Terapis\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Terapis\ProfilController::class, 'update'])->name('profil.update');
});
