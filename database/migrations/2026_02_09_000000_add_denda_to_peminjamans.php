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
            $table->decimal('denda_amount', 12, 2)->default(0)->after('status');
            $table->string('denda_status')->default('unpaid')->after('denda_amount'); // unpaid, paid, waived
            $table->string('denda_type')->nullable()->after('denda_status'); // rusak, terlambat, hilang, lain
            $table->text('denda_reason')->nullable()->after('denda_type');
            $table->unsignedBigInteger('denda_set_by')->nullable()->after('denda_reason');
            $table->integer('keterlambatan_hari')->default(0)->after('denda_set_by');
            $table->string('kondisi_pengembalian')->nullable()->after('keterlambatan_hari');

            $table->foreign('denda_set_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropForeign(['denda_set_by']);
            $table->dropColumn([
                'denda_amount',
                'denda_status',
                'denda_type',
                'denda_reason',
                'denda_set_by',
                'keterlambatan_hari',
                'kondisi_pengembalian',
            ]);
        });
    }
};
