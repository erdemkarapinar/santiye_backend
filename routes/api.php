<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SantiyeController;
use App\Http\Controllers\Api\GunlukKayitController;
use App\Http\Controllers\Api\GorevlerController;
use App\Http\Controllers\Api\KayitlarController;
use App\Http\Controllers\Api\RaporController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/santiye', [SantiyeController::class, 'index']);
Route::post('/santiye', [SantiyeController::class, 'store']);

Route::get('/gunlukkayit', [GunlukKayitController::class, 'index']);
Route::post('/gunlukkayit', [GunlukKayitController::class, 'store']);

Route::get('/gorev', [GorevlerController::class, 'index']);
Route::post('/gorev', [GorevlerController::class, 'store']);

Route::get('/kayit', [KayitlarController::class, 'index']);
Route::post('/kayit', [KayitlarController::class, 'store']);

Route::get('/raporlar', [RaporController::class, 'index']);