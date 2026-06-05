<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\GraphController;

Route::get('/', [HomeController::class, 'landing']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
Route::get('/graph', [GraphController::class, 'index'])->name('graph.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Module 2 — Document Management
    Route::resource('documents', DocumentController::class)
         ->except(['edit', 'update']);

    // Module 6 — RAG Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/', [ChatController::class, 'create'])->name('create');
        Route::get('{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('{conversation}/send', [ChatController::class, 'send'])->name('send');
        Route::delete('{conversation}', [ChatController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';