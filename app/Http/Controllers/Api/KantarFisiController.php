<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KantarFisi;

class KantarFisiController extends Controller
{
    public function index()
    {
        $kantarFisleri = KantarFisi::with([
            'user',
            'santiye'
        ])
        ->latest()
        ->get();

        return response()->json([
            'message' => 'Kantar kayıtları başarıyla getirildi.',
            'data' => $kantarFisleri
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',

            'fis_fotografi' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',

            'urun_cinsi' => 'required|string|max:255',

            'tedarikci' => 'required|string|max:255',

            'giris_kg' => 'required|numeric|min:0',

            'cikis_kg' => 'required|numeric|min:0',

            'tarih' => 'required|date',

            'not' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Fiş fotoğrafı
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('fis_fotografi')) {
            $validated['fis_fotografi'] =
                $request->file('fis_fotografi')
                    ->store('kantar-fisleri', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Net ağırlık hesaplama
        |--------------------------------------------------------------------------
        */

        $validated['net_agirlik'] =
            $validated['giris_kg'] - $validated['cikis_kg'];

        /*
        |--------------------------------------------------------------------------
        | Giriş yapan kullanıcı
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Kayıt oluştur
        |--------------------------------------------------------------------------
        */

        $kantarFisi = KantarFisi::create($validated);

        return response()->json([
            'message' => 'Kantar fiş kaydı başarıyla oluşturuldu.',
            'data' => $kantarFisi->load([
                'user',
                'santiye'
            ])
        ], 201);
    }
}
