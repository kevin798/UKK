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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('gambar_pengembalian')->nullable()->after('kondisi_pengembalian');
            $table->text('catatan_pengembalian')->nullable()->after('gambar_pengembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn([
                'gambar_pengembalian',
                'catatan_pengembalian',
            ]);
        });
    }
};
