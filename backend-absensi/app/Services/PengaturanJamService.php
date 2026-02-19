<?php

namespace App\Services;

use App\Exceptions\PengaturanErrorException;
use App\Models\PengaturanJam;

class PengaturanJamService
{
    public function getPengaturanByDay(string $hari)
    {
        $pengaturan_jam = PengaturanJam::select(
            'nama',
            'jam_masuk_awal',
            'jam_masuk_akhir',
            'jam_pulang_awal',
            'jam_pulang_akhir'
        )
            ->where('nama', $hari)
            ->first();

        if (empty($pengaturan_jam)) {
            return throw new PengaturanErrorException('Tidak ditemukan pengaturan jam di sistem');
        }

        return $pengaturan_jam;
    }
}
