<?php

namespace Database\Seeders;

use App\Models\PengaturanJam;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PengaturanJamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hari_array = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        foreach ($hari_array as $hari) {
            PengaturanJam::create([
                'nama' => $hari,
                'jam_masuk_awal' => '06:00:00',
                'jam_masuk_akhir' => '07:00:00',
                'jam_pulang_awal' => '12:00:00',
                'jam_pulang_akhir' => '13:00:00',
            ]);
        }
    }
}
