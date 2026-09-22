<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SantiyeController;
use App\Http\Controllers\Api\GunlukKayitController;
use App\Http\Controllers\Api\GorevlerController;
use App\Http\Controllers\Api\KayitlarController;
use App\Http\Controllers\Api\RaporController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HataliImalatController;
use App\Http\Controllers\Api\MalzemeStoguController;
use App\Http\Controllers\Api\BetonFisiController;
use App\Http\Controllers\Api\KantarFisiController;
use App\Http\Controllers\Api\IrsaliyeController;
use App\Http\Controllers\Api\ReferansController;
use App\Http\Controllers\Api\EkibimController;
use App\Http\Controllers\Api\PuantajController;
use App\Http\Controllers\Api\MetrajController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/santiye', [SantiyeController::class, 'index']);
    Route::post('/santiye', [SantiyeController::class, 'store']);

    Route::get('/gunlukkayit', [GunlukKayitController::class, 'index']);
    Route::post('/gunlukkayit', [GunlukKayitController::class, 'store']);

    Route::get('/gorev', [GorevlerController::class, 'index']);
    Route::post('/gorev', [GorevlerController::class, 'store']);

    Route::get('/kayit', [KayitlarController::class, 'index']);
    Route::post('/kayit', [KayitlarController::class, 'store']);

    Route::get('/raporlar', [RaporController::class, 'index']);

    Route::post('/hatali-imalatlar', [
        HataliImalatController::class,
        'store'
    ]);
    Route::get('/hatali-imalatlar', [
        HataliImalatController::class,
        'index'
    ]);
    Route::get('/malzeme-stoklari', [
        MalzemeStoguController::class,
        'index'
    ]);

    Route::post('/malzeme-stoklari', [
        MalzemeStoguController::class,
        'store'
    ]);
    Route::get('/beton-fisleri', [
        BetonFisiController::class,
        'index'
    ]);

    Route::post('/beton-fisleri', [
        BetonFisiController::class,
        'store'
    ]);

    Route::get('/kantar-fisleri', [
        KantarFisiController::class,
        'index'
    ]);

    Route::post('/kantar-fisleri', [
        KantarFisiController::class,
        'store'
    ]);

    Route::get('/irsaliyeler', [IrsaliyeController::class, 'index']);
    Route::post('/irsaliyeler', [IrsaliyeController::class, 'store']);

    Route::post('/referanslar', [ReferansController::class, 'store']);

    Route::get('/ekibim', [EkibimController::class, 'index']);
    Route::post('/ekibim/{user}/santiye-ata', [
        EkibimController::class,
        'santiyeAta'
    ]);

    Route::get(
        '/ekibim/{user}/santiyeler',
        [EkibimController::class, 'personelinSantiyeleri']
    );
    Route::delete(
        '/ekibim/{user}/santiye/{santiye}',
        [EkibimController::class, 'santiyeCikar']
    );
    Route::get('/puantaj', [PuantajController::class, 'index']);

    Route::post('/metraj', [MetrajController::class, 'store']);
});

