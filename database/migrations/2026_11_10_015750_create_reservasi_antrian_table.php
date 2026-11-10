<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi_antrean', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('paket_layanan_id')->constrained('paket_layanan')->onDelete('cascade');
            $table->foreignId('terapis_id')->constrained('terapis')->onDelete('cascade');
            $table->date('tanggal_praktik');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('nomor_antrean');
            $table->enum('status_antrean', ['menunggu_pembayaran', 'terjadwal', 'berlangsung', 'selesai', 'batal'])
                  ->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi_antrean');
    }
};
