<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MalzemeStogu;
use Illuminate\Http\Request;

class MalzemeStoguController extends Controller
{
    public function index()
    {
        $malzemeler = MalzemeStogu::with([
            'user',
            'santiye'
        ])->latest()->get();

        return response()->json([
            'message' => 'Malzeme stokları başarıyla getirildi.',
            'data' => $malzemeler
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',
            'malzeme_adi' => 'required|string|max:255',
            'birim' => 'required|string|max:50',
            'miktar' => 'required|numeric|min:0',
            'min_stok' => 'required|numeric|min:0',
            'birim_fiyat' => 'required|numeric|min:0',
            'not' => 'nullable|string',
        ]);

        // Kaydı oluşturan giriş yapmış kullanıcı
        $validated['user_id'] = $request->user()->id;

        $malzeme = MalzemeStogu::create($validated);

        return response()->json([
            'message' => 'Malzeme stoğu başarıyla oluşturuldu.',
            'data' => $malzeme
        ], 201);
    }
}
