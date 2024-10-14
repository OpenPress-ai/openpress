<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageBuilderController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::prefix('admin')->group(function () {
        Route::get('/page-builder', [PageBuilderController::class, 'index'])->name('page-builder.index');
        Route::get('/pages/create', [PageBuilderController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageBuilderController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageBuilderController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageBuilderController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [PageBuilderController::class, 'destroy'])->name('pages.destroy');
    });
});

Route::prefix('api/page-builder')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/elements', [PageBuilderController::class, 'getElements']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';