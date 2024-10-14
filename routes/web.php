<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageBuilderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::prefix('admin')->group(function () {
        Route::get('/page-builder', [PageBuilderController::class, 'index'])->name('page-builder.index');
        Route::get('/page-builder/create', [PageBuilderController::class, 'create'])->name('page-builder.create');
        Route::post('/page-builder', [PageBuilderController::class, 'store'])->name('page-builder.store');
        Route::get('/page-builder/{page}/edit', [PageBuilderController::class, 'edit'])->name('page-builder.edit');
        Route::put('/page-builder/{page}', [PageBuilderController::class, 'update'])->name('page-builder.update');
        Route::delete('/page-builder/{page}', [PageBuilderController::class, 'destroy'])->name('page-builder.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';