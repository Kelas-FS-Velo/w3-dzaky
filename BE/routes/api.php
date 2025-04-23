<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); 

Route::get('/profile', [UserController::class, 'index']);

Route::prefix('user')->group(function (){
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});

Route::prefix('book')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::post('/add', [BookController::class, 'store']);
    Route::delete('/delete/{id}', [BookController::class, 'destroy']);
    Route::prefix('/{id}')->group(function () {
        Route::get('/', [BookController::class, 'search']);
        Route::put('/update', [BookController::class, 'update']);
    });
});

Route::prefix('genre')->group(function () {
    Route::get('/', [GenreController::class, 'index']);
    Route::post('/add', [GenreController::class, 'store']);
});