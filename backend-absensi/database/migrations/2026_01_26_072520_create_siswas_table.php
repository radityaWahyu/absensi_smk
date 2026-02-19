<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP TYPE IF EXISTS jenis_kelamin");
        DB::statement("CREATE TYPE jenis_kelamin AS ENUM('l','p')");

        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama', 100)->index('index_siswa_nama');
            $table->char('jenis_kelamin', '2');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE siswa ALTER COLUMN jenis_kelamin TYPE jenis_kelamin USING jenis_kelamin::text::jenis_kelamin");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('siswa');
    }
};
