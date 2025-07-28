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
    Route::get('/tasks/create', [TasksPageController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TasksPageController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TasksPageController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TasksPageController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TasksPageController::class, 'destroy'])->name('tasks.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';