<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BetonFisi;
use Illuminate\Http\Request;

class BetonFisiController extends Controller
{
    public function index()
    {
        $betonFisleri = BetonFisi::with([
            'user',
            'santiye'
        ])->latest()->get();

        return response()->json([
            'message' => 'Beton fişleri başarıyla getirildi.',
            'data' => $betonFisleri
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',

            'beton_fotosu' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',

            'irsaliye_fotosu' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',

            'beton_sinifi' => 'required|in:C16,C20,C25,C30,C35,C40,C45',

            'tedarikci' => 'required|string|max:255',

            'miktar' => 'required|numeric|min:0',

            'arac_plakasi' => 'required|string|max:20',

            'tarih' => 'required|date',

            'not' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Beton fotoğrafı
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('beton_fotosu')) {
            $validated['beton_fotosu'] =
                $request->file('beton_fotosu')
                    ->store('beton-fisleri', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | İrsaliye fotoğrafı
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('irsaliye_fotosu')) {
            $validated['irsaliye_fotosu'] =
                $request->file('irsaliye_fotosu')
                    ->store('beton-fisleri', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Giriş yapan kullanıcı
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = auth()->id();

        $betonFisi = BetonFisi::create($validated);

        return response()->json([
            'message' => 'Beton fişi başarıyla oluşturuldu.',
            'data' => $betonFisi->load([
                'user',
                'santiye'
            ])
        ], 201);
    }
}
