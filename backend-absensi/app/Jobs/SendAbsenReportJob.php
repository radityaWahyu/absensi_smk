<?php

namespace App\Jobs;

use App\Models\Siswa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAbsenReportJob implements ShouldQueue
{
    use Queueable;

    protected $id_siswa;
    protected $latitude;
    protected $longitude;
    protected $tgl_absen;
    protected $jam_absen;
    protected $status;
    protected $jarak;
    protected $url_whatsapp_server;


    /**
     * Create a new job instance.
     */
    public function __construct(
        $id_siswa,
        $latitude,
        $longitude,
        $tgl_absen,
        $jam_absen,
        $status,
        $jarak,
    ) {
        $this->id_siswa = $id_siswa;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->tgl_absen = $tgl_absen;
        $this->jam_absen = $jam_absen;
        $this->status = $status;
        $this->jarak = $jarak;
        $this->url_whatsapp_server = env('WHATSAPP_SERVER_API', 'http://192.168.22.12:3000');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $siswa = Siswa::select('id', 'nama')
                ->where('id', $this->id_siswa)
                ->first();

            $message = "Ananda *" . Str::upper($siswa->nama) . "* telah melakukan *ABSEN " . $this->status . "* dengan keterangan sebagai berikut :\n*Tanggal Absen :" . $this->tgl_absen . "* \n*Jam Datang : " . $this->jam_absen . "* \n*Jarak : " . $this->jarak . "* \n*Link Peta :* https://maps.google.com/maps/search/?api=1&query=" . $this->latitude . "," . $this->longitude;

            /** @var Response $coba */
            $response = Http::asForm()
                ->withBasicAuth('admin', 'admin')->withHeaders([
                    'Accept' => 'application/json',
                ])->post(
                    $this->url_whatsapp_server . '/send/message',
                    [
                        'phone' => '6285649926667@s.whatsapp.net',
                        'message' => $message,
                        'is_forwarded' => false,
                        'duration' => 0
                    ]
                );

            Log::info('Laporan absen berhasil dikirim ke pengguna');
        } catch (RequestException $error) {
            Log::error('Kirim whatsaap laporan absen error : ' . $error->getMessage());
        }
    }
}
