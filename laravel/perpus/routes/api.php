<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\RegisterController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/test', function (Request $request) {
//     return response()->json("Test OK", 200);;
// });


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout', [RegisterController::class, 'logout']);
    Route::get('/buku', [BukuController::class, 'index']);

    Route::controller(BukuController::class)->group(function(){
        Route::get('/book/buku', 'index');
        Route::post('/book/store', 'store');
        Route::get('/book/show/{id}', 'show');
        Route::post('/book/update', 'update');
        Route::get('/book/delete/{id}', 'destroy');
        Route::post('/book/cari', 'search');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/login', [RegisterController::class, 'login'])->name('login');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [RegisterController::class, 'login'])->name('login');





    

