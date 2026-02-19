<?php

namespace App;

enum tipe_absen_enum: int
{
    case ABSEN_DATANG = 1;
    case IJIN = 2;
    case SAKIT = 3;
    case ALPHA = 4;
    case TIDAK_ABSEN_PULANG = 5;
    case ABSEN_FULL = 6;
}
