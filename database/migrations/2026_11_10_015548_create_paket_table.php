<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->string('foto_paket')->nullable(); // Path lokasi simpan foto banner/gambar paket
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_menit');
            $table->decimal('harga', 12, 2);
            $table->boolean('status_publish')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_layanan');
    }
};
