<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageBuilderController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('admin/page-builder')->group(function () {
        Route::get('/', [PageBuilderController::class, 'index'])->name('page-builder.index');
        Route::get('/create', [PageBuilderController::class, 'create'])->name('page-builder.create');
        Route::get('/{id}/edit', [PageBuilderController::class, 'edit'])->name('page-builder.edit');
    });
});

Route::prefix('api/page-builder')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/elements', [PageBuilderController::class, 'getElements']);
    Route::post('/pages', [PageBuilderController::class, 'store']);
});

require __DIR__.'/auth.php';