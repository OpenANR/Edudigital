<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return 'Dashboard Admin';
        })->name('admin.dashboard');
    });

    Route::middleware('role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', function () {
            return 'Dashboard Guru';
        })->name('guru.dashboard');
    });

    Route::middleware('role:wali_kelas')->prefix('wali_kelas')->group(function () {
        Route::get('/dashboard', function () {
            return 'Dashboard Wali Kelas';
        })->name('wali_kelas.dashboard');
    });
});

require __DIR__.'/auth.php';
