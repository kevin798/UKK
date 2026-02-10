<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->unique(['alat_id', 'kategori_id']);
        });

        // Copy existing single kategori_id into pivot
        DB::table('alats')
            ->select('id', 'kategori_id')
            ->whereNotNull('kategori_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('alat_kategori')->insert([
                        'alat_id' => $row->id,
                        'kategori_id' => $row->kategori_id,
                    ]);
                }
            });

        Schema::table('alats', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->cascadeOnDelete();
        });

        Schema::dropIfExists('alat_kategori');
    }
};
