<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('peminjaman_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->unsignedBigInteger('petugas_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->unsignedBigInteger('petugas_id')
                ->nullable(false)
                ->change();
        });
    }
};