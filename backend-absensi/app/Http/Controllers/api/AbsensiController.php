<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Services\AbsensiService;
use App\Http\Requests\AbsenRequest;
use App\Services\PengaturanService;
use App\Http\Controllers\Controller;



class AbsensiController extends Controller
{
    private $user;
    public function __construct(private AbsensiService $absensi_service,)
    {
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PengaturanService $pengaturan_service)
    {
        // ambil data kordinat dari pengaturan
        $pengaturan = $pengaturan_service->getKoordinatSekolah();

        // ambil data absensi hari ini sesuai dengan siswa
        $absensi = $this->absensi_service
            ->getStatusAbsensiNow($this->user->userable_id);


        $response_absensi = [
            'id_siswa' => $absensi->id ?? null,
            'nama' => $absensi->nama ?? null,
            'tgl_absen' => !empty($absensi->tgl_absen) ? Carbon::parse($absensi->tgl_absen)->isoFormat('dddd, D MMMM Y') : null,
            'jam_datang' => $absensi->jam_datang ?? null,
            'jarak_datang' => !empty($absensi->jarak_datang) ? human_read_distance($absensi->jarak_datang) : null,
            'jam_pulang' => $absensi->jam_pulang ?? null,
            'jarak_pulang' => !empty($absensi->jarak_pulang) ? human_read_distance($absensi->jarak_pulang) : null,
            'tipe_absen' => $absensi->tipe_absen ?? null,
            'keterangan' => $absensi->keterangan ?? null,
        ];


        return response()->json([
            'success' => true,
            'message' => 'Data siswa ditemukan',
            'data' => [
                'pengaturan' => $pengaturan,
                'absensi' => $response_absensi
            ]
        ]);
    }


    public function absen_datang(AbsenRequest $request)
    {

        $form_request = $request->validated();

        $absensi = $this->absensi_service->absen_datang(
            $this->user->userable_id,
            $form_request['latitude'],
            $form_request['longitude']
        );

        $response_absensi = [
            'id_siswa' => $absensi['id_siswa'],
            'latitude' => $absensi['lat_datang'],
            'longitude' => $absensi['long_datang'],
            'tgl_absen' => Carbon::parse($absensi['tgl_absen'])->isoFormat('dddd, D MMMM Y'),
            'jam' => $absensi['jam_datang'],
            'jarak' => human_read_distance($absensi['jarak_datang']),
            'tipe_absen' => $absensi['tipe_absen']
        ];

        return response()->json([
            'success' => true,
            'message' => $absensi['message'],
            'data' => $response_absensi
        ]);
    }

    public function absen_pulang(AbsenRequest $request)
    {

        $form_request = $request->validated();

        $absensi = $this->absensi_service->absen_pulang(
            $this->user->userable_id,
            $form_request['latitude'],
            $form_request['longitude']
        );

        $response_absensi = [
            'id_siswa' => $this->user->userable_id,
            'latitude' => $absensi['lat_pulang'],
            'longitude' => $absensi['long_pulang'],
            'tgl_absen' => Carbon::parse($absensi['tgl_absen'])->isoFormat('dddd, D MMMM Y'),
            'jam' => $absensi['jam_pulang'],
            'jarak' => human_read_distance($absensi['jarak_pulang']),
            'tipe_absen' => $absensi['tipe_absen']
        ];

        return response()->json([
            'success' => true,
            'message' => $absensi['message'],
            'data' => $response_absensi
        ]);
    }
}
