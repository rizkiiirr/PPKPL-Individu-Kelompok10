<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoilLocationController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\TeknisiSondirController;
use App\Http\Controllers\SoilCertificateController;
use App\Http\Controllers\PetugasLapanganController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PetugasLabController;

/*
|--------------------------------------------------------------------------
| Route Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Fitur Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Dashboard Sementara untuk testing login
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');


/*
|--------------------------------------------------------------------------
| Route Netral (Tanpa ID) - UNTUK DEVELOPMENT
|--------------------------------------------------------------------------
*/
// Route::get('/', [SoilLocationController::class, 'create'])->name('home');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/location/create', [SoilLocationController::class, 'create'])->name('locations.create.simple');
Route::post('/location/store', [SoilLocationController::class, 'store'])->name('locations.store.simple');


/*
|--------------------------------------------------------------------------
| Route Relational (Dengan SoilTest) - UNTUK PRODUCTION / DATA SUDAH ADA
|--------------------------------------------------------------------------
*/
// Dibungkus middleware auth agar hanya actor yang login yang bisa input/lihat data
Route::middleware('auth')->group(function () {

    // US 1.1: Pengajuan untuk Kontraktor
    Route::prefix('kontraktor')->group(function () {
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan/simpan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    });
    Route::prefix('pemilik')->group(function () {

        // Dashboard pemilik
        Route::get('/dashboard', [PemilikController::class, 'index'])
            ->name('pemilik.index');

        // Form tambah proyek
        Route::get('/proyek/create', [PemilikController::class, 'create'])
            ->name('proyek.create');

        // Simpan proyek
        Route::post('/proyek/store', [PemilikController::class, 'store'])
            ->name('proyek.store');

        // Notifikasi
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::get(
            '/dashboard',
            [PemilikController::class, 'index']
        )->name('pemilik.index');

        Route::get(
            '/proyek/create',
            [PemilikController::class, 'create']
        )->name('proyek.create');

        Route::post(
            '/proyek/store',
            [PemilikController::class, 'store']
        )->name('proyek.store');

        Route::get(
            '/notifications',
            [NotificationController::class, 'index']
        )->name('notifications.index');

    });

    // US 1.2 Pengajuan input lokasi petugas lapangan
    Route::prefix('petugas_lapangan')->group(function () {

        Route::get('/lokasi', [PetugasLapanganController::class, 'index'])->name('lokasi.index');

        Route::get('/lokasi/{soilTest}/buat', [PetugasLapanganController::class, 'create'])->name('lokasi.create');

        Route::post('/lokasi/{soilTest}/simpan', [PetugasLapanganController::class, 'store'])->name('lokasi.store');

    });
    Route::prefix('petugas_lab')->middleware('auth')->group(function () {

        Route::get(
            '/dashboard',
            [PetugasLabController::class, 'index']
        )->name('petugas_lab.index');

        Route::post(
            '/kelayakan/{soilTest}',
            [PetugasLabController::class, 'updateKelayakan']
        )->name('lab.kelayakan.update');

    });
        

    Route::prefix('lab')->group(function () {

        // ================= US 1.2 =================
        Route::get('/penjadwalan', [SoilLocationController::class, 'index'])
            ->name('lab.lokasi.index');

        Route::get('/penjadwalan/{soilTest}/buat', [SoilLocationController::class, 'create'])
            ->name('lab.lokasi.create');

        Route::post('/penjadwalan/{soilTest}/simpan', [SoilLocationController::class, 'store'])
            ->name('lab.lokasi.store');

        Route::delete('/penjadwalan/{soilTest}/revert', [SoilLocationController::class, 'revert'])
            ->name('lab.lokasi.revert');


        // ================= US 1.4 =================
        Route::get('/certificate/{soilTest}/upload', [SoilCertificateController::class, 'create'])
            ->name('lab.certificate.create');

        Route::post('/certificate/{soilTest}/upload', [SoilCertificateController::class, 'store'])
            ->name('lab.certificate.store');

    });

    // US 1.3: Rute untuk Teknisi Lapangan
    Route::prefix('teknisi')->middleware('auth')->group(function () {
        Route::get('/sondir', [TeknisiSondirController::class, 'index'])->name('teknisi.sondir.index');
        Route::get('/sondir/{lokasi}/input', [TeknisiSondirController::class, 'create'])->name('teknisi.sondir.create');
        Route::post('/sondir/{lokasi}', [TeknisiSondirController::class, 'store'])->name('teknisi.sondir.store');

        Route::delete('/sondir/{hasil}/revert', [TeknisiSondirController::class, 'revert'])->name('teknisi.sondir.revert');
    });

    Route::prefix('pemilik')->middleware('auth')->group(function () {
        // kirim dari lab
        Route::post('/lab/notify/{soilTest}', [NotificationController::class, 'send'])
            ->name('lab.notify');

        // lihat oleh pemilik
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
    });
});