<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->restrictOnDelete();

            $table->string('nama_alat');
            $table->string('kode_alat')->unique();
            $table->string('gambar')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->string('kondisi', 50);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};