<?php

use Illuminate\Support\Facades\Routes;
use App\Http\Controllers\Api\TaskController;

Route::prefix('tasks')->group(function(){
    Route::get('/',[TaskController::class,'index']);
    Route::post('/',[TaskController::class,'store']);
    Route::get('{id}',[TaskController::class,'show']);
    Route::put('{id}',[TaskController::class,'update']);
    Route::delete('{id}',[TaskController::class,'destroy']);
});
