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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('location_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nama_barang');

            $table->text('deskripsi')->nullable();

            $table->string('kategori')->nullable();

            $table->string('variasi')->nullable();

            $table->string('ukuran')->nullable();

            $table->enum('status_barang', [
                'available',
                'reserved',
                'taken',
                'completed'
            ])->default('available');

            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
