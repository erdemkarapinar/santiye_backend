<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Santiye;
use Illuminate\Http\Request;

class SantiyeController extends Controller
{
    public function index()
    {
        $santiye = Santiye::all();

        return response()->json([
            'success' => true,
            'message' => 'Şantiye başarıyla oluşturuldu.',
            'data' => $santiye,
        ], 200);
    }

    // Yeni santiye ekleme
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firma_adi'        => 'required|string|max:255',
            'santiye_adi'      => 'required|string|max:255',
            'baslangic_zamani' => 'required|date',
            'bitis_zamani'     => 'required|date|after:baslangic_zamani',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
        ]);

        $santiye = Santiye::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Santiye başarıyla oluşturuldu.',
            'data' => $santiye,
        ], 201);
    }
}
