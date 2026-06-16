<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaliController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\GuruController;

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Route::get('/managedepartment', [AdminController::class, 'manageDepartment'])->name('admin.manageDepartment');
        // Route::get('/managestudent', [AdminController::class, 'manageStudent'])->name('admin.manageStudent');
        Route::get('/managescore', [AdminController::class, 'manageScore'])->name('admin.manageScore');
        Route::get('/filter', [AdminController::class, 'manageScore'])->name('admin.manageScore');
        Route::post('/savescore', [AdminController::class, 'saveScore'])->name('admin.saveScore');
        Route::post('/savesettings', [AdminController::class, 'saveSettings'])->name('admin.saveSettings');
    });

    Route::middleware('role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
        Route::get('/managescore', [GuruController::class, 'manageScore'])->name('guru.manageScore');
        Route::post('/savescore', [GuruController::class, 'saveScore'])->name('guru.saveScore');
    });

    Route::middleware('role:wali_kelas')->prefix('wali_kelas')->group(function () {
        Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('wali_kelas.dashboard');
        Route::get('/viewstudent', [WaliController::class, 'viewStudent'])->name('wali_kelas.viewStudent');
    });
});

require __DIR__.'/auth.php';
