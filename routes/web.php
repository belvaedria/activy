<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::middleware('auth')->group(function () {
    // Route untuk menampilkan halaman form
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    
    // Route untuk memproses data form saat disubmit
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');

    // Menampilkan daftar aktivitas
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // Menampilkan halaman rekapitulasi
    Route::get('/rekapitulasi', [ActivityController::class, 'recap'])->name('activities.recap');
    
    // Menghapus aktivitas
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    
    // (Opsional untuk nanti) Route untuk edit
    Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
