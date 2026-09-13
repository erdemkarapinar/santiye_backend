<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SantiyeController;

Route::get('/santiye', [SantiyeController::class, 'index']);

Route::post('/santiye', [SantiyeController::class, 'store']);
