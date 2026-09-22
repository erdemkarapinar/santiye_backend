<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Metraj;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MetrajController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'metraj_tipi' => [
                'required',
                'in:beton,doseme,duvar,kazi,demir,seramik'
            ],

            'santiye_id' => [
                'nullable',
                'exists:santiyes,id'
            ],
        ]);

        $tip = $validated['metraj_tipi'];

        $sonuc = null;
        $birim = null;

        /*
        |--------------------------------------------------------------------------
        | BETON
        |--------------------------------------------------------------------------
        */

        if ($tip === 'beton') {

            $data = $request->validate([
                'uzunluk' => ['required', 'numeric', 'gt:0'],
                'genislik' => ['required', 'numeric', 'gt:0'],
                'kalinlik' => ['required', 'numeric', 'gt:0'],
            ]);

            $sonuc =
                $data['uzunluk'] *
                $data['genislik'] *
                $data['kalinlik'];

            $birim = 'm³';

            $metrajData = $data;
        }

        /*
        |--------------------------------------------------------------------------
        | DÖŞEME
        |--------------------------------------------------------------------------
        */

        elseif ($tip === 'doseme') {

            $data = $request->validate([
                'uzunluk' => ['required', 'numeric', 'gt:0'],
                'genislik' => ['required', 'numeric', 'gt:0'],
            ]);

            $sonuc =
                $data['uzunluk'] *
                $data['genislik'];

            $birim = 'm²';

            $metrajData = $data;
        }

        /*
        |--------------------------------------------------------------------------
        | DUVAR
        |--------------------------------------------------------------------------
        */

        elseif ($tip === 'duvar') {

            $data = $request->validate([
                'uzunluk' => ['required', 'numeric', 'gt:0'],
                'yukseklik' => ['required', 'numeric', 'gt:0'],
                'kapi_sayisi' => ['required', 'integer', 'min:0'],
                'pencere_sayisi' => ['required', 'integer', 'min:0'],
            ]);

            // Brüt duvar alanı
            $brutAlan =
                $data['uzunluk'] *
                $data['yukseklik'];

            // Her kapı = 1.89 m²
            $kapiAlani =
                $data['kapi_sayisi'] * 1.89;

            // Her pencere = 1.44 m²
            $pencereAlani =
                $data['pencere_sayisi'] * 1.44;

            // Net alan
            $sonuc =
                $brutAlan -
                $kapiAlani -
                $pencereAlani;

            // Negatif alan oluşmasını engelle
            if ($sonuc < 0) {
                throw ValidationException::withMessages([
                    'metraj' => [
                        'Kapı ve pencere alanları toplamı duvar alanından büyük olamaz.'
                    ]
                ]);
            }

            $birim = 'm²';

            $metrajData = $data;
        }

        /*
        |--------------------------------------------------------------------------
        | KAZI
        |--------------------------------------------------------------------------
        */

        elseif ($tip === 'kazi') {

            $data = $request->validate([
                'uzunluk' => ['required', 'numeric', 'gt:0'],
                'genislik' => ['required', 'numeric', 'gt:0'],
                'derinlik' => ['required', 'numeric', 'gt:0'],
            ]);

            $sonuc =
                $data['uzunluk'] *
                $data['genislik'] *
                $data['derinlik'];

            $birim = 'm³';

            $metrajData = [
                'uzunluk' => $data['uzunluk'],
                'genislik' => $data['genislik'],
                'kalinlik' => $data['derinlik'],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DEMİR
        |--------------------------------------------------------------------------
        */

        elseif ($tip === 'demir') {

            $data = $request->validate([
                'demir_cap' => [
                    'required',
                    'numeric',
                    'in:0.395,0.617,0.888,1.208,1.578,1.998,2.466,2.984,3.853,6.313'
                ],

                'demir_adet' => [
                    'required',
                    'integer',
                    'min:1'
                ],

                'demir_boy' => [
                    'required',
                    'numeric',
                    'gt:0'
                ],
            ]);

            $birimAgirliklari = [
                0.395 => 0.395,
                0.617 => 0.617,
                0.888 => 0.888,
                1.208 => 1.208,
                1.578 => 1.578,
                1.998 => 1.998,
                2.466 => 2.466,
                2.984 => 2.984,
                3.853 => 3.853,
                6.313 => 6.313,
            ];

            $cap = (float) $data['demir_cap'];

            $birimAgirlik = $birimAgirliklari[$cap];

            $sonuc =
                $data['demir_adet'] *
                $data['demir_boy'] *
                $birimAgirlik;

            $birim = 'kg';

            $metrajData = [
                'demir_cap' => $data['demir_cap'],
                'demir_adet' => $data['demir_adet'],
                'demir_boy' => $data['demir_boy'],
                'demir_birim_agirlik' => $birimAgirlik,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SERAMİK
        |--------------------------------------------------------------------------
        */

        elseif ($tip === 'seramik') {

            $data = $request->validate([
                'uzunluk' => ['required', 'numeric', 'gt:0'],
                'genislik' => ['required', 'numeric', 'gt:0'],
                'fire_orani' => ['required', 'numeric', 'min:0'],
            ]);

            $netAlan =
                $data['uzunluk'] *
                $data['genislik'];

            $fireMiktari =
                $netAlan *
                ($data['fire_orani'] / 100);

            $sonuc =
                $netAlan +
                $fireMiktari;

            $birim = 'm²';

            $metrajData = $data;
        }

        /*
        |--------------------------------------------------------------------------
        | KAYDET
        |--------------------------------------------------------------------------
        */

        $metraj = Metraj::create([
            'user_id' => $request->user()->id,

            'santiye_id' => $validated['santiye_id'] ?? null,

            'metraj_tipi' => $tip,

            'uzunluk' => $metrajData['uzunluk'] ?? null,
            'genislik' => $metrajData['genislik'] ?? null,
            'kalinlik' => $metrajData['kalinlik'] ?? null,
            'yukseklik' => $metrajData['yukseklik'] ?? null,

            'kapi_sayisi' => $metrajData['kapi_sayisi'] ?? null,
            'pencere_sayisi' => $metrajData['pencere_sayisi'] ?? null,

            'fire_orani' => $metrajData['fire_orani'] ?? null,

            'demir_cap' => $metrajData['demir_cap'] ?? null,
            'demir_adet' => $metrajData['demir_adet'] ?? null,
            'demir_boy' => $metrajData['demir_boy'] ?? null,
            'demir_birim_agirlik' => $metrajData['demir_birim_agirlik'] ?? null,

            'sonuc' => $sonuc,
            'birim' => $birim,
        ]);

        $metraj->load([
            'user',
            'santiye',
        ]);

        return response()->json([
            'message' => 'Metraj başarıyla hesaplandı.',
            'data' => $metraj,
        ], 201);
    }
}