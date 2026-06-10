<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/managedepartment', [AdminController::class, 'manageDepartment'])->name('admin.manageDepartment');
        Route::get('/managestudent', [AdminController::class, 'manageStudent'])->name('admin.manageStudent');
    });

    Route::middleware('role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', function () {
            return view('roleView.guru.guru');
        })->name('guru.dashboard');
    });

    Route::middleware('role:wali_kelas')->prefix('wali_kelas')->group(function () {
        Route::get('/dashboard', function () {
            return view('roleView.waliKelas.waliKelas');
        })->name('wali_kelas.dashboard');
    });
});

require __DIR__.'/auth.php';
