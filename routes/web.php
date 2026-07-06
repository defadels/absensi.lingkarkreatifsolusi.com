<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Hrd;
use App\Http\Controllers\Pegawai;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Root Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

// Offline Fallback Page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// Central dashboard redirect
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================
// PEGAWAI Routes
// =============================================
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [Pegawai\AbsensiController::class, 'dashboard'])->name('dashboard');

    // Absensi
    Route::get('/absensi/masuk', [Pegawai\AbsensiController::class, 'masukForm'])->name('absensi.masuk');
    Route::post('/absensi/masuk', [Pegawai\AbsensiController::class, 'masukStore'])->name('absensi.masuk.store');
    Route::get('/absensi/pulang', [Pegawai\AbsensiController::class, 'pulangForm'])->name('absensi.pulang');
    Route::post('/absensi/pulang', [Pegawai\AbsensiController::class, 'pulangStore'])->name('absensi.pulang.store');
    Route::get('/riwayat', [Pegawai\AbsensiController::class, 'riwayat'])->name('riwayat');

    // Izin
    Route::get('/izin', [Pegawai\IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/create', [Pegawai\IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [Pegawai\IzinController::class, 'store'])->name('izin.store');
});

// =============================================
// ADMIN Routes
// =============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_pegawai'  => \App\Models\Pegawai::count(),
            'hadir_hari_ini' => \App\Models\Absensi::where('tanggal', now()->toDateString())->count(),
            'pending_izin'   => \App\Models\Izin::where('status_persetujuan', 'pending')->count(),
            'lokasi_aktif'   => \App\Models\LokasiKerja::where('is_active', true)->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    // Pegawai management
    Route::get('/pegawai', [Admin\PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{pegawai}', [Admin\PegawaiController::class, 'show'])->name('pegawai.show');
    Route::get('/pegawai/{pegawai}/edit', [Admin\PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::patch('/pegawai/{pegawai}', [Admin\PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [Admin\PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    // Lokasi kerja management
    Route::get('/lokasi-kerja', [Admin\LokasiKerjaController::class, 'index'])->name('lokasi-kerja.index');
    Route::get('/lokasi-kerja/create', [Admin\LokasiKerjaController::class, 'create'])->name('lokasi-kerja.create');
    Route::post('/lokasi-kerja', [Admin\LokasiKerjaController::class, 'store'])->name('lokasi-kerja.store');
    Route::get('/lokasi-kerja/{lokasiKerja}/edit', [Admin\LokasiKerjaController::class, 'edit'])->name('lokasi-kerja.edit');
    Route::patch('/lokasi-kerja/{lokasiKerja}', [Admin\LokasiKerjaController::class, 'update'])->name('lokasi-kerja.update');
    Route::patch('/lokasi-kerja/{lokasiKerja}/toggle', [Admin\LokasiKerjaController::class, 'toggleActive'])->name('lokasi-kerja.toggle');
    Route::delete('/lokasi-kerja/{lokasiKerja}', [Admin\LokasiKerjaController::class, 'destroy'])->name('lokasi-kerja.destroy');

    // Absensi monitoring
    Route::get('/absensi', [Admin\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/rekap', [Admin\AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/export-pdf', [Admin\AbsensiController::class, 'exportPdf'])->name('absensi.export-pdf');
    Route::get('/absensi/{absensi}', [Admin\AbsensiController::class, 'show'])->name('absensi.show');
});

// =============================================
// HRD Routes
// =============================================
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/dashboard', [Hrd\AbsensiController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [Hrd\AbsensiController::class, 'monitoring'])->name('monitoring');
    Route::get('/laporan', [Hrd\AbsensiController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-pdf', [Hrd\AbsensiController::class, 'exportPdf'])->name('laporan.export-pdf');

    // Izin verification
    Route::get('/izin', [Hrd\IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/{izin}', [Hrd\IzinController::class, 'show'])->name('izin.show');
    Route::patch('/izin/{izin}/approve', [Hrd\IzinController::class, 'approve'])->name('izin.approve');
    Route::patch('/izin/{izin}/reject', [Hrd\IzinController::class, 'reject'])->name('izin.reject');
});

require __DIR__ . '/auth.php';
