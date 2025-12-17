<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//tasks

Route::middleware('auth:sanctum')->prefix('tasks')->group(function(){
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::post('add', [TaskController::class, 'store']);
    Route::delete('delete/{id}',[TaskController::class,'destroy']);
    Route::post('update/{id}',[TaskController::class,'update']); 

});




//register
Route::post('/register', [AuthController::class, 'register']);
//login
Route::post('/login', [AuthController::class, 'login']);