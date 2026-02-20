<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\AbsensiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/coba', function (Request $request) {
    return 'berhasil berjalan sempurna';
});


Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/refresh', [AuthController::class, 'refresh_token'])->name('refresh');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::name('absensi.')->prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('status');
        Route::post('/datang', [AbsensiController::class, 'absen_datang'])->name('datang');
        Route::post('/pulang', [AbsensiController::class, 'absen_pulang'])->name('pulang');
    });
});
