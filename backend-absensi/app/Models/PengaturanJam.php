<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PengaturanJam extends Model
{

    protected $table = "pengaturan_jam";
    protected $fillable = ['nama', 'jam_masuk_awal', 'jam_masuk_akhir', 'jam_pulang_awal', 'jam_pulang_akhir'];

    protected $casts = [
        'jam_masuk_awal' => 'datetime:H:i:s',
        'jam_masuk_akhir' => 'datetime:H:i:s',
        'jam_pulang_awal' => 'datetime:H:i:s',
        'jam_pulang_akhir' => 'datetime:H:i:s',
    ];
}
