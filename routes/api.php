<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController; // Add semicolon here


// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});



Route::get('tasks',        [TaskController::class, 'index']);   // List all tasks
Route::post('tasks',       [TaskController::class, 'store']);   // Create a new task
Route::get('tasks/{task}', [TaskController::class, 'show']);    // Get a single task
Route::put('tasks/{task}', [TaskController::class, 'update']);  // Update a task
// Route::patch('tasks/{task}', [TaskController::class, 'update']); // Optional: PATCH support
Route::delete('tasks/{task}', [TaskController::class, 'destroy']); // Delete a task


