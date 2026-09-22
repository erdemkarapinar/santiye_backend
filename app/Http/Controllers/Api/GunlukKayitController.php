<?php

namespace App\Http\Controllers\Api;
use App\Models\GunlukKayit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
            'personeller' => 'nullable|array',
            'personeller.*' => 'integer|exists:users,id',
        ]);
        
        $personelIds = $validated['personeller'] ?? [];

        $uygunsuzPersoneller = User::whereIn('id', $personelIds)
            ->whereDoesntHave('santiyeler', function ($query) use ($validated) {
                $query->where('santiyes.id', $validated['santiye_id']);
            })
            ->pluck('id');

        if ($uygunsuzPersoneller->isNotEmpty()) {
            return response()->json([
                'message' => 'Bazı personeller bu şantiyeye atanmamış.',
                'personel_idleri' => $uygunsuzPersoneller,
            ], 422);
        }

        $kayit = GunlukKayit::create($validated);
        $gunlukKayit->personeller()->sync(
            $validated['personeller'] ?? []
        );

        $gunlukKayit->load([
            'user',
            'santiye',
            'personeller.role',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Günlük kayıt başarıyla oluşturuldu.',
            'data' => $kayit,
        ], 201);
    }
}
