<?php

namespace App\Services;

use App\Exceptions\AbsenDeniedException;
use App\Models\Absensi;
use App\Services\PengaturanJamService;
use App\Services\PengaturanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AbsensiService
{

    protected $now;
    /**
     * Create a new class instance.
     */
    public function __construct(
        private PengaturanJamService $pengaturan_jam,
        private PengaturanService $pengaturan_service
    ) {
        $this->now = Carbon::now();
    }


    public function checkAbsensiDatangNow(string $id_siswa): bool
    {
        // $now = Carbon::now();

        $absensi = DB::table('absensi')
            ->select('id_siswa')
            ->where('id_siswa', $id_siswa)
            ->whereDate('tgl_absen', $this->now->format('Y-m-d'))
            ->whereNotNull('jam_datang')
            ->first();

        if ($absensi) {
            return true;
        }

        return false;
    }

    public function checkAbsensiPulangNow(string $id_siswa): bool
    {
        // $now = Carbon::now();

        $absensi = DB::table('absensi')
            ->select('id_siswa')
            ->where('id_siswa', $id_siswa)
            ->whereDate('tgl_absen', $this->now->format('Y-m-d'))
            ->whereNotNull('jam_datang')
            ->whereNotNull('jam_pulang')
            ->first();

        if ($absensi) {
            return true;
        }

        return false;
    }

    public function absen_datang(
        string $id_siswa,
        float $latitude,
        float $longitude
    ) {
        // $now = Carbon::now();
        $tgl_absen = $this->now->format('Y-m-d');
        $jam_datang = $this->now->format("H:i:s");
        $hari = $this->now->isoFormat('dddd');
        $message = "Absen datang berhasil disimpan";


        // Pengambilan data pengaturan sesuai dengan hari tersebut
        $pengaturan = $this->pengaturan_jam
            ->getPengaturanByDay($hari);

        // Pengambilan data koordinat awal dari sekolah
        $koordinat_sekolah = $this->pengaturan_service
            ->getKoordinatSekolah();

        // Menghitung jarak antara koordinat sekolah dengan koordinat siswa yang telah absen
        $jarak_datang = get_distance(
            $koordinat_sekolah['latitude'],
            $koordinat_sekolah['longitude'],
            $latitude,
            $longitude
        );


        $fields = [
            'id_siswa' => $id_siswa,
            'lat_datang' => $latitude,
            'long_datang' => $longitude,
            'tgl_absen' => $tgl_absen,
            'jam_datang' => $jam_datang,
            'jarak_datang' => $jarak_datang,
            'tipe_absen' => 1
        ];


        // Pengecekan apakah siswa telah melakukan absen pada hari tersebut.
        $absensi_datang = $this->checkAbsensiDatangNow($id_siswa);

        if ($absensi_datang) {
            throw new AbsenDeniedException('Anda sudah melakukan absensi datang');
        }

        $absen_mulai = Carbon::parse($pengaturan->jam_masuk_awal);
        $absen_berakhir = Carbon::parse($pengaturan->jam_masuk_akhir);

        // Pengecekan absensi apabila melakukan absen sebelum waktu yang ditentukan
        if ($this->now->isBefore($absen_mulai)) {
            throw new AbsenDeniedException('Anda belum diijinkan untuk absen datang');
        }

        // Pengecekan absensi apabila melakukan absen melebihi waktu yang ditentukan
        if ($this->now->isAfter($absen_berakhir)) {
            $selisih = $absen_berakhir->diff($this->now);
            if ($selisih->h == 0) {
                $message = 'Anda melebihi jam masuk sebanyak ' . $selisih->i . ' menit';
            } else {
                $message = 'Anda melebihi jam masuk sebanyak ' . $selisih->h . ' jam ' . $selisih->i . ' menit';
            }
            $fields += ["datang_timeout" => $absen_berakhir];
        }


        try {


            // Menyimpan data absensi ke dalam database
            Absensi::create($fields);


            // Kirim notifikasi whatsaap kepada orang tua siswa sesuai dengan no hp yang aktif.
            // SendAbsenReportJob::dispatch(
            //     $id_siswa,
            //     $latitude,
            //     $longitude,
            //     Carbon::parse($tgl_absen)->isoFormat('dddd, D MMMM Y'),
            //     $jam_datang,
            //     'DATANG',
            //     human_read_distance($jarak_datang)
            // );

            $fields += ["message" => $message];

            // Kembalikan data absensi
            return $fields;
        } catch (\Exception $e) {
            Log::error("Error :" . $e->getMessage());
        }
    }

    public function absen_pulang(
        string $id_siswa,
        float $latitude,
        float $longitude
    ) {
        // $now = Carbon::now();
        $tgl_absen = $this->now->format('Y-m-d');
        $jam_pulang = $this->now->format("H:i:s");
        $hari = $this->now->isoFormat('dddd');
        $message = "Absen pulang berhasil disimpan";


        // Pengambilan data pengaturan sesuai dengan hari tersebut
        $pengaturan = $this->pengaturan_jam
            ->getPengaturanByDay($hari);

        // Pengambilan data koordinat awal dari sekolah
        $koordinat_sekolah = $this->pengaturan_service
            ->getKoordinatSekolah();

        // Menghitung jarak antara koordinat sekolah dengan koordinat siswa yang telah absen
        $jarak_pulang = get_distance(
            $koordinat_sekolah['latitude'],
            $koordinat_sekolah['longitude'],
            $latitude,
            $longitude
        );


        $fields = [
            'lat_pulang' => $latitude,
            'long_pulang' => $longitude,
            'jam_pulang' => $jam_pulang,
            'jarak_pulang' => $jarak_pulang,

        ];


        // Pengecekan apakah siswa telah melakukan absen pada hari tersebut.
        $absensi_datang = $this->checkAbsensiPulangNow($id_siswa);

        if ($absensi_datang) {
            throw new AbsenDeniedException('Anda sudah melakukan absensi pulang');
        }

        $absen_mulai = Carbon::parse($pengaturan->jam_pulang_awal);
        $absen_berakhir = Carbon::parse($pengaturan->jam_pulang_akhir);

        // Pengecekan absensi apabila melakukan absen sebelum waktu yang ditentukan
        if ($this->now->isBefore($absen_mulai)) {
            throw new AbsenDeniedException('Anda belum diijinkan untuk absen pulang');
        }

        // Pengecekan absensi apabila melakukan absen melebihi waktu yang ditentukan
        if ($this->now->isAfter($absen_berakhir)) {
            $selisih = $absen_berakhir->diff($this->now);
            if ($selisih->h == 0) {
                $message = ' Anda dianggap tidak absen pulang  karena melebihi jam pulang sebanyak ' . $selisih->i . ' menit';
            } else {
                $message = 'Anda dianggap tidak absen pulang  karena melebihi jam pulang sebanyak ' . $selisih->h . ' jam ' . $selisih->i . ' menit';
            }

            $fields += ['tipe_absen' => 5];
        } else {
            $fields += ['tipe_absen' => 6];
        }


        try {


            // Menyimpan data absensi ke dalam database
            Absensi::where('id_siswa', $id_siswa)
                ->where('tgl_absen', $this->now->format('Y-m-d'))
                ->update($fields);


            // if ($fields['tipe_absen'] == 6) {
            // Kirim notifikasi whatsaap kepada orang tua siswa sesuai dengan no hp yang aktif.
            // SendAbsenReportJob::dispatch(
            //     $id_siswa,
            //     $latitude,
            //     $longitude,
            //     Carbon::parse($tgl_absen)->isoFormat('dddd, D MMMM Y'),
            //     $jam_datang,
            //     'PULANG',
            //     human_read_distance($jarak_datang)
            // );
            // }

            $fields += [
                "tgl_absen" => $this->now->format('Y-m-d'),
                "message" => $message
            ];

            // Kembalikan data absensi
            return $fields;
        } catch (\Exception $e) {
            Log::error("Error :" . $e->getMessage());
        }
    }

    public function getStatusAbsensiNow(String $id_siswa)
    {
        $absensi = DB::table('siswa')
            ->join('absensi', 'siswa.id', '=', 'absensi.id_siswa')
            ->select(
                'siswa.id',
                'siswa.nama',
                'absensi.tgl_absen',
                'absensi.jam_datang',
                'absensi.jarak_datang',
                'absensi.jam_pulang',
                'absensi.jarak_pulang',
                'absensi.tipe_absen',
                'absensi.keterangan'
            )
            ->whereDate('tgl_absen', $this->now->format('Y-m-d'))
            ->first();

        return $absensi;
    }
}
