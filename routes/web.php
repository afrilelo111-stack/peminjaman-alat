<?php

use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\User\AlatController as UserAlatController;
use App\Http\Controllers\User\PeminjamanController as UserPeminjamanController;
use App\Http\Controllers\User\PengembalianController as UserPengembalianController;
use App\Http\Controllers\Petugas\LaporanController as PetugasLaporanController;
use App\Models\Alat;
use App\Models\Kategori;


Route::get('/', function (\Illuminate\Http\Request $request) {
    $alats = Alat::with('kategori')
        ->when($request->search, function($q, $search) {
            $q->where('nama_alat', 'like', "%{$search}%")
              ->orWhere('kode_alat', 'like', "%{$search}%");
        })
        ->when($request->kategori_id, function($q, $katId) {
            $q->where('kategori_id', $katId);
        })
        ->latest()
        ->paginate(10);

    $kategoris = Kategori::all();

    return view('welcome', compact('alats', 'kategoris'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        //CRUD Pengguna
        Route::resource('users', UserController::class)
            ->except(['show']);

        //CRUD Kategori
        Route::resource('kategoris',KategoriController::class)
            ->except(['show']);

        //CRUD Alat
        Route::resource('alats',AlatController::class)
            ->except(['show']);

        //CRUD Peminjaman
        Route::resource('peminjamans', PeminjamanController::class)
            ->except(['show']);

        //CRUD Pengembalian
        Route::resource('pengembalians', PengembalianController::class)
            ->except(['show']);

        //Activity Log
        Route::get('activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');
    });

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->name('dashboard');

        // Peminjaman
        Route::get('/peminjamans', [PetugasPeminjamanController::class, 'index'])
            ->name('peminjamans.index');

        Route::get('/peminjamans/{peminjaman}', [PetugasPeminjamanController::class, 'show'])
            ->name('peminjamans.show');

        Route::patch('/peminjamans/{peminjaman}/status', [
            PetugasPeminjamanController::class,
            'updateStatus'
        ])->name('peminjamans.update-status');

        // Pengembalian
        Route::get('/pengembalians', [PetugasPengembalianController::class, 'index'])
            ->name('pengembalians.index');
        
        Route::get('/pengembalians/{peminjaman}', [PetugasPengembalianController::class, 'show'])
            ->name('pengembalians.show');
        
        Route::patch('/pengembalians/{pengembalian}/status', [
            PetugasPengembalianController::class,
            'updateStatus'
        ])->name('pengembalians.update-status');

        // Laporan
Route::get('/laporan', [PetugasLaporanController::class, 'index'])
    ->name('laporan.index');

Route::get('/laporan/peminjaman', [PetugasLaporanController::class, 'exportPeminjaman'])
    ->name('laporan.peminjaman');

Route::get('/laporan/pengembalian', [PetugasLaporanController::class, 'exportPengembalian'])
    ->name('laporan.pengembalian');
    });

// Route User
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/alats', [UserAlatController::class, 'index'])
            ->name('alats.index');

        Route::get('/alats/{alat}', [UserAlatController::class, 'show'])
            ->name('alats.show');

        Route::get('/peminjamans/{alat}', [UserPeminjamanController::class, 'create'])
            ->name('peminjamans.index');

        Route::post('/peminjamans/{alat}', [UserPeminjamanController::class, 'store'])
            ->name('peminjamans.store');

        Route::get('/riwayat-peminjamans', [UserPengembalianController::class, 'index'])
            ->name('riwayat-peminjamans.index');

        Route::get('/riwayat-peminjamans/{peminjaman}', [UserPengembalianController::class, 'show'])
            ->name('riwayat-peminjamans.show');

        Route::get('/riwayat-peminjamans/{peminjaman}/pengembalian', [UserPengembalianController::class, 'create'])
            ->name('riwayat-peminjamans.create');

        Route::post('/riwayat-peminjamans/{peminjaman}/pengembalian', [UserPengembalianController::class, 'store'])
            ->name('riwayat-peminjamans.store');

    });

require __DIR__.'/auth.php';
