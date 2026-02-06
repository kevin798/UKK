<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\ActivityLogController;

Route::redirect('/', '/auth/login');

Route::middleware(['guest'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');

        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register'])->name('register.post');
    });
});

Route::middleware(['auth'])->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:admin'])->group(function () {

        Route::get('admin/dashboard', [AdminController::class, 'showDashboard'])
            ->name('admin.dashboard');

        Route::resource('alat', AlatController::class);

        Route::prefix('admin')->group(function () {

            Route::get('peminjam', [UserController::class, 'peminjamList'])
                ->name('admin.peminjam');

            Route::get('peminjam/edit/{id}', [UserController::class, 'editPeminjamForm'])
                ->name('admin.peminjam.edit');

            Route::put('peminjam/update', [UserController::class, 'updatePeminjam'])
                ->name('admin.peminjam.update');

            Route::delete('peminjam/delete/{id}', [UserController::class, 'deletePeminjam'])
                ->name('admin.peminjam.delete');

            Route::prefix('petugas')->group(function () {

                Route::get('/', [UserController::class, 'petugasList'])
                    ->name('admin.petugas');

                Route::get('/create', [UserController::class, 'createPetugasForm'])
                    ->name('admin.petugas.create');

                Route::post('/create', [UserController::class, 'createPetugas'])
                    ->name('admin.petugas.store');

                Route::get('/edit/{user}', [UserController::class, 'editPetugasForm'])
                    ->name('admin.petugas.edit');

                Route::put('/update/{user}', [UserController::class, 'updatePetugas'])
                    ->name('admin.petugas.update');

                Route::delete('/delete/{user}', [UserController::class, 'deletePetugas'])
                    ->name('admin.petugas.delete');
            });

            Route::resource('kategori', KategoriController::class);

            Route::get('riwayat', [UserController::class, 'riwayatList'])
                ->name('admin.riwayat');
        });
    });

    Route::middleware(['role:petugas'])->group(function () {

        Route::get('petugas/dashboard', [PetugasController::class, 'showDashboard'])
            ->name('petugas.dashboard');

        Route::get('petugas/peminjaman', [PetugasController::class, 'showPeminjamanList'])
            ->name('petugas.peminjaman');

        Route::post('petugas/peminjaman/{id}/approve', [PetugasController::class, 'approvePeminjaman'])
            ->name('petugas.peminjaman.approve');

        Route::post('petugas/peminjaman/{id}/reject', [PetugasController::class, 'rejectPeminjaman'])
            ->name('petugas.peminjaman.reject');

        Route::post('petugas/peminjaman/{id}/return', [PetugasController::class, 'returnPeminjaman'])
            ->name('petugas.peminjaman.return');
    });

    Route::middleware(['role:user'])->group(function () {

        Route::get('user/dashboard', [PeminjamController::class, 'showDashboard'])
            ->name('user.dashboard');

        Route::get('user/log-aktivitas', [ActivityLogController::class, 'index'])
            ->name('log.aktivitas');

        Route::get('user/profile/edit', [ProfileController::class, 'edit'])
            ->name('user.profile.edit');

        Route::put('user/profile', [ProfileController::class, 'update'])
            ->name('user.profile.update');

        Route::get('user/alat', [AlatController::class, 'userList'])
            ->name('user.alat-list');

        Route::get('user/peminjaman', [PeminjamanController::class, 'index'])
            ->name('user.peminjaman');

        Route::get('user/peminjaman/create', [PeminjamanController::class, 'create'])
            ->name('user.peminjaman.create');

        Route::post('user/peminjaman', [PeminjamanController::class, 'store'])
            ->name('user.peminjaman.store');

        Route::get('user/peminjaman/{id}', [PeminjamanController::class, 'show'])
            ->name('user.peminjaman.show');

        Route::get('user/users', [UserController::class, 'userList'])
            ->name('user.users');
    });
});
