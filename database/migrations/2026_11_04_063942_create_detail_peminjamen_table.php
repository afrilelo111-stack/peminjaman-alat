<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peminjaman_id')
                ->constrained('peminjamans')
                ->cascadeOnDelete();

            $table->foreignId('alat_id')
                ->constrained('alats')
                ->restrictOnDelete();

            $table->unsignedInteger('jumlah');

            $table->timestamps();

            $table->unique(['peminjaman_id', 'alat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};