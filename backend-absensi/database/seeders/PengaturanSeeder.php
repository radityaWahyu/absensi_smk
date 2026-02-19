<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $referensi_koordinat = array(
            [
                'name' => 'latitude_sekolah',
                'value' => '-7.767642883427563'
            ],
            [
                'name' => 'longitude_sekolah',
                'value' => '112.74782691186091'
            ]
        );


        foreach ($referensi_koordinat as $pengaturan) {
            Pengaturan::create($pengaturan);
        }
    }
}
