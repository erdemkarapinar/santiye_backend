<?php

namespace App\Http\Controllers\Api;
use App\Models\GunlukKayit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GunlukKayitController extends Controller
{
        public function index()
    {
        $kayit = GunlukKayit::all();

        return response()->json([
            'success' => true,
            'message' => 'Günlük kayıt başarıyla oluşturuldu.',
            'data' => $kayit,
        ], 200);
    }
        public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id'   => 'required|exists:santiyes,id',
            'weather'      => 'nullable|string|max:255',
            'temperature'  => 'nullable|numeric',
            'not'          => 'required|string|max:255',
            'ekipman'      => 'required|string|max:255',
            'toplam_ucret'  => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ]);

        $kayit = GunlukKayit::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Günlük kayıt başarıyla oluşturuldu.',
            'data' => $kayit,
        ], 201);
    }
}
