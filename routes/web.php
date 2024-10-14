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

    Route::prefix('admin/page-builder')->group(function () {
        Route::get('/', [PageBuilderController::class, 'index'])->name('page-builder.index');
        Route::get('/create', [PageBuilderController::class, 'create'])->name('page-builder.create');
        Route::get('/{id}/edit', [PageBuilderController::class, 'edit'])->name('page-builder.edit');
        // Add the following new routes
        Route::get('/pages', [PageBuilderController::class, 'index'])->name('page-builder.pages');
        Route::get('/pages/create', [PageBuilderController::class, 'create'])->name('page-builder.pages.create');
        Route::get('/pages/{id}/edit', [PageBuilderController::class, 'edit'])->name('page-builder.pages.edit');
    });
});

Route::prefix('api/page-builder')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/elements', [PageBuilderController::class, 'getElements']);
    Route::post('/pages', [PageBuilderController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';