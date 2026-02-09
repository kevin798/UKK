<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'status_barang')) {
                $table->string('status_barang')->nullable()->after('kondisi_pengembalian');
            }
            if (!Schema::hasColumn('peminjamans', 'catatan_pengembalian')) {
                $table->text('catatan_pengembalian')->nullable()->after('status_barang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'status_barang')) {
                $table->dropColumn('status_barang');
            }
            if (Schema::hasColumn('peminjamans', 'catatan_pengembalian')) {
                $table->dropColumn('catatan_pengembalian');
            }
        });
    }
};
