<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\JenisKelaminEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::create([
            'nama' => 'Raditya Wahyu',
            'jenis_kelamin' => JenisKelaminEnum::LAKI_LAKI
        ]);

        $siswa->user()->create([
            'username' => '1234',
            'password' => Hash::make('123456'),
            'email' => 'radityaw@gmail.com'
        ]);
    }
}
