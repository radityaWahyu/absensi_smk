<?php

use App\Models\Siswa;
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
        Schema::create('absensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Siswa::class, 'id_siswa');
            $table->date('tgl_absen');
            $table->time('jam_datang');
            $table->decimal('lat_datang', 10, 7);
            $table->decimal('long_datang', 10, 7);
            $table->integer('jarak_datang')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->decimal('lat_pulang', 10, 7)->nullable();
            $table->decimal('long_pulang', 10, 7)->nullable();
            $table->integer('jarak_pulang')->nullable();
            $table->time('datang_timeout')->nullable();
            $table->integer('tipe_absen')->default(1);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
