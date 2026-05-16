<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_donasi', function (Blueprint $table) {
          $table->id();

            $table->foreignId('item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('alasan_kebutuhan');

            $table->string('bukti_identitas')->nullable();

            $table->enum('metode_ambil', [
                'ambil_sendiri',
                'pesan_antar'
            ])->default('ambil_sendiri');

            $table->string('alamat_kirim')->nullable();
            $table->string('kelurahan_kirim')->nullable();
            $table->string('kecamatan_kirim')->nullable();
            $table->string('kota_kirim')->nullable();

            $table->enum('status_pengajuan', [
                'pending',
                'accepted',
                'rejected',
                'completed'
            ])->default('pending');

            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_donasi');
    }
};
