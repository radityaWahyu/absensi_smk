<?php

namespace App\Models;

use App\JenisKelaminEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Siswa extends Model
{
    use HasUuids;

    protected $table = 'siswa';


    protected $casts = [
        'jenis_kelamin' => JenisKelaminEnum::class,
    ];


    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
