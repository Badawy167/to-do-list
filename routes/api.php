<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Api;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Middleware\Authenticate;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//register
Route::post('/register', [AuthController::class, 'register']);
//login
Route::post('/login', [AuthController::class, 'login']);

//search products
Route::get('/products/search', [ProductController::class, 'search']);

//products
Route::middleware('auth:sanctum')->prefix('products')->group(function(){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('add', [ProductController::class, 'store']);
    Route::post('update/{id}',[ProductController::class,'update']); 
    Route::delete('delete/{id}',[ProductController::class,'destroy']);
});

