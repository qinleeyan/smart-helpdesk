<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::get('tickets/export', [\App\Http\Controllers\TicketController::class, 'export'])->name('tickets.export');
    Route::resource('tickets', \App\Http\Controllers\TicketController::class);

    // Knowledge Base
    Route::get('kb', [App\Http\Controllers\ArticleController::class, 'kb'])->name('kb.index');
    Route::get('kb/suggest', [App\Http\Controllers\ArticleController::class, 'suggest'])->name('kb.suggest');
    Route::get('kb/admin', [App\Http\Controllers\ArticleController::class, 'adminIndex'])->name('kb.admin.index');
    Route::get('kb/admin/create', [App\Http\Controllers\ArticleController::class, 'create'])->name('kb.admin.create');
    Route::post('kb/admin', [App\Http\Controllers\ArticleController::class, 'store'])->name('kb.admin.store');
    Route::get('kb/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('kb.show');

    // Analytics
    Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
});

require __DIR__ . '/auth.php';
