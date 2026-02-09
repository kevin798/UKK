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
            if (!Schema::hasColumn('peminjamans', 'foto_pengembalian')) {
                $table->string('foto_pengembalian')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('peminjamans', 'kondisi_pengembalian')) {
                $table->string('kondisi_pengembalian')->nullable()->after('foto_pengembalian');
            }
            if (!Schema::hasColumn('peminjamans', 'keterlambatan_hari')) {
                $table->integer('keterlambatan_hari')->nullable()->after('kondisi_pengembalian');
            }
            if (!Schema::hasColumn('peminjamans', 'denda_type')) {
                $table->string('denda_type')->nullable()->after('keterlambatan_hari');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $cols = ['foto_pengembalian', 'kondisi_pengembalian', 'keterlambatan_hari', 'denda_type'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('peminjamans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
