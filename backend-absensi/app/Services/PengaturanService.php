<?php

namespace App\Services;

use App\Exceptions\PengaturanErrorException;
use App\Models\Pengaturan;

class PengaturanService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getKoordinatSekolah()
    {
        $pengaturan = Pengaturan::select('name', 'value')
            ->whereIn('name', ['latitude_sekolah', 'longitude_sekolah'])
            ->get();

        if (is_null($pengaturan[0]->value) || is_null($pengaturan[1]->value)) {
            throw new PengaturanErrorException('Pengaturan Koordinat sekolah belum di setting');
        }


        $latitude = $pengaturan[0]->value;
        $longitude = $pengaturan[1]->value;

        return [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }
}
