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
        Schema::create('pengaturan_jam', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 7)->index('index_hari_pengaturan');
            $table->time('jam_masuk_awal')->nullable();
            $table->time('jam_masuk_akhir')->nullable();
            $table->time('jam_pulang_awal')->nullable();
            $table->time('jam_pulang_akhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_jam');
    }
};
