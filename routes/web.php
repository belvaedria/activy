<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecapController;

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware(['auth'])
 ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route untuk menampilkan halaman form
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    
    // Route untuk memproses data form saat disubmit
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');

    // Menampilkan daftar aktivitas
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // Menampilkan halaman rekapitulasi
    Route::get('/rekapitulasi',
        [RecapController::class, 'index'])
        ->name('activities.recap');

    // Menghapus aktivitas
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    
    // Route untuk edit
    Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');

    Route::get('/plans', [PlanController::class, 'index'])
        ->name('plans.index');

    Route::post('/plans', [PlanController::class, 'store'])
        ->name('plans.store');

    Route::delete('/plans/{id}', [PlanController::class, 'destroy'])
        ->name('plans.destroy');

    Route::get('/plans/{id}/edit', [PlanController::class, 'edit'])
        ->name('plans.edit');

    Route::put('/plans/{id}', [PlanController::class, 'update'])
        ->name('plans.update');
});

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
