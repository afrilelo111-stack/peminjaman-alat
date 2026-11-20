
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pengembalians', function (Blueprint $table) {
            $table->string('gambar_bukti')->nullable()->after('kondisi_alat');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengembalians', function (Blueprint $table) {
            $table->dropColumn('gambar_bukti');
        });
    }
};
