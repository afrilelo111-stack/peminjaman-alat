
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengembalians', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengembalian_id')
                ->constrained('pengembalians')
                ->cascadeOnDelete();

            $table->foreignId('detail_peminjaman_id')
                ->constrained('detail_peminjamans')
                ->restrictOnDelete();

            $table->unsignedInteger('jumlah_dikembalikan');

            $table->string('kondisi_alat', 50);

            $table->unique([
                'pengembalian_id',
                'detail_peminjaman_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalians');
    }
};
