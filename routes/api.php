<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SantiyeController;
use App\Http\Controllers\Api\GunlukKayitController;

Route::get('/santiye', [SantiyeController::class, 'index']);
Route::post('/santiye', [SantiyeController::class, 'store']);

Route::get('/kayit', [GunlukKayitController::class, 'index']);
Route::post('/kayit', [GunlukKayitController::class, 'store']);
