<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// API route for checking if the API works
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// API Resource routes for Task CRUD
Route::apiResource('tasks', TaskController::class);
