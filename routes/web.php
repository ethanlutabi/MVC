<?php

use App\Http\Controllers\TasksPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    
    Route::get('/tasks', [TasksPageController::class, 'index'])->name('tasks.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

