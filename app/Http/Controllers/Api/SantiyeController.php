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
   public function store(Request $request)
    {
        $validated = $request->validate([
            'firma_adi' => 'required|string|max:255',
            'santiye_adi' => 'required|string|max:255',
            'baslangic_zamani' => 'nullable|date',
            'bitis_zamani' => 'nullable|date|after_or_equal:baslangic_zamani',
            'location' => 'nullable|string|max:255',
        ]);

        // Giriş yapan kullanıcı
        $user = $request->user();

        // Şantiyeyi oluştur
        $santiye = Santiye::create([
            'firma_adi' => $validated['firma_adi'],
            'santiye_adi' => $validated['santiye_adi'],
            'baslangic_zamani' => $validated['baslangic_zamani'] ?? null,
            'bitis_zamani' => $validated['bitis_zamani'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        // Şantiyeyi oluşturan kullanıcıyı otomatik olarak
        // santiye_user pivot tablosuna ekle
        $santiye->users()->syncWithoutDetaching([
            $user->id
        ]);

        // İlişkileri yükle
        $santiye->load([
            'user',
            'users'
        ]);

        return response()->json([
            'message' => 'Şantiye başarıyla oluşturuldu.',
            'data' => $santiye
        ], 201);
    }
}
