<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SantiyeController;
use App\Http\Controllers\Api\GunlukKayitController;
use App\Http\Controllers\Api\GorevlerController;

Route::get('/santiye', [SantiyeController::class, 'index']);
Route::post('/santiye', [SantiyeController::class, 'store']);

Route::get('/kayit', [GunlukKayitController::class, 'index']);
Route::post('/kayit', [GunlukKayitController::class, 'store']);

Route::get('/gorev', [GorevlerController::class, 'index']);
Route::post('/gorev', [GorevlerController::class, 'store']);