<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\RegistrasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UangSakuController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\Admin\ContentController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ================= LOGIN =================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// ================= LOGOUT =================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ================= SEMUA HARUS LOGIN =================
Route::middleware(['auth'])->group(function () {

    // ===== ADMIN =====
    Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrasi', [RegistrasiController::class, 'index'])->name('registrasi.index');
    //Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    
    // Manajemen User (Menggantikan logic Register lama)
    Route::get('/registrasi', [RegisterController::class, 'index'])->name('registrasi.index');
    Route::get('/registrasi/create', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/registrasi/store', [RegisterController::class, 'register'])->name('register.store');
    Route::delete('/registrasi/{id_user}', [RegisterController::class, 'destroy'])->name('register.destroy');

    // ===== SANTRI (CRUD) =====
    Route::resource('santri', SantriController::class);

    Route::post('/santri/import', [SantriController::class, 'importExcel'])->name('santri.import');

    //route jenis pembayaran
    Route::resource('jenis-pembayaran', JenisPembayaranController::class);
        
        Route::middleware(['auth'])->group(function () {
    
        // Fitur Ganti Password User
        Route::get('/change-password', [ProfileController::class, 'showPasswordForm'])->name('password.edit');
        Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

        // --- FITUR CMS KONTEN ---
        Route::get('/settings', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
        Route::post('/settings', [App\Http\Controllers\Admin\ContentController::class, 'update'])->name('content.update');


});
    });



// ===== KEAMANAN (INPUT ABSENSI) =====
Route::prefix('keamanan')->name('keamanan.')->group(function () {
    // Dashboard keamanan
    Route::get('/dashboard', [DashboardController::class, 'keamanan'])
            ->name('dashboard');

    // Route::get('/absensi/{nis}', [AbsensiController::class, 'index'])
    //     ->name('absensi.index');

    // Route::get('/absensi/{nis}/create', [AbsensiController::class, 'create'])
    //     ->name('absensi.create');

    // Route::post('/absensi/{nis}', [AbsensiController::class, 'store'])
    //     ->name('absensi.store');

    Route::delete('/absensi/{id_absensi}', [AbsensiController::class, 'destroy'])
        ->name('absensi.destroy');

    Route::get('/absensi-harian', [AbsensiController::class, 'inputHarian'])
        ->name('absensi.harian');

    Route::post('/absensi-harian', [AbsensiController::class, 'storeHarian'])
        ->name('absensi.harian.store');

    Route::get('/histori', [AbsensiController::class, 'historiSantri'])
    ->name('histori');

    // 🔹 HISTORI
    Route::get('/absensi/{nis}', [AbsensiController::class, 'index'])
        ->name('absensi.index');
});

// ===== BENDAHARA =====
Route::prefix('bendahara')->name('bendahara.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'bendahara'])
        ->name('dashboard');

    //route fitur pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show'); 
    Route::post('/pembayaran/{id}/approve', [PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('/pembayaran/{id}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject'); 
    // TAMBAHKAN BARIS INI:
    Route::post('/pembayaran/{id}/set-belum-lunas', [PembayaranController::class, 'setBelumLunas'])->name('pembayaran.setBelumLunas');

    Route::get('/pembayaran/get-santri/{nis}', [PembayaranController::class, 'getSantriData'])->name('pembayaran.getSantri');

    // Pembayaran Manual (Halaman Berbeda)
    Route::get('/pembayaran-manual', [PembayaranController::class, 'createManual'])->name('pembayaran.manual');
    Route::post('/pembayaran-manual', [PembayaranController::class, 'storeManual'])->name('pembayaran.storeManual');

    // Route Fitur Rekapitulasi & Histori Keseluruhan
    Route::get('/rekap-pembayaran', [PembayaranController::class, 'rekapSantri'])->name('pembayaran.rekap');
    Route::get('/rekap-pembayaran/{nis}', [PembayaranController::class, 'detailRekapSantri'])->name('pembayaran.rekap.detail');
    // route fitur uang saku
    Route::get('/uang-saku', [UangSakuController::class, 'index'])->name('uangsaku.index');
    Route::get('/uang-saku/{nis}', [UangSakuController::class, 'show'])->name('uangsaku.show');
    Route::get('/uang-saku/{nis}/create', [UangSakuController::class, 'create'])->name('uangsaku.create');
    Route::post('/uang-saku/{nis}/store', [UangSakuController::class, 'store'])->name('uangsaku.store');
});


// ===== USER =====
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');

    // fitur absensi
    Route::get('/absensi', [AbsensiController::class, 'myAbsensi'])->name('absensi.index');

    //fitur pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/riwayat', [PembayaranController::class, 'riwayat'])->name('pembayaran.index');

    // fitur uang saku
    Route::get('/uang-saku', [UangSakuController::class, 'indexUser'])->name('uangsaku.index');

    // Fitur Ganti Password User
    Route::get('/change-password', [ProfileController::class, 'showPasswordForm'])->name('password.edit');      
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

});

});