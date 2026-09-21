<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referans;
use Illuminate\Http\Request;

class ReferansController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $user = $request->user();

        // Önce şantiyeyi bul
        $santiye = \App\Models\Santiye::find($validated['santiye_id']);

        // Kullanıcı şantiyenin sahibi/oluşturucusu mu?
        $santiyeSahibiMi = $santiye->user_id == $user->id;

        // Yoksa santiye_user pivot tablosunda atanmış mı?
        $santiyeUyesiMi = $user->santiyeler()
            ->where('santiyes.id', $validated['santiye_id'])
            ->exists();

        // Hiçbiri değilse yetki yok
        if (!$santiyeSahibiMi && !$santiyeUyesiMi) {
            return response()->json([
                'message' => 'Bu şantiye için referans oluşturma yetkiniz yok.'
            ], 403);
        }

        $referans = Referans::create([
            'olusturan_user_id' => $user->id,
            'santiye_id' => $validated['santiye_id'],
            'role_id' => $validated['role_id'],
            'kod' => Referans::generateCode(),
            'kullanildi_mi' => false,
            'kullanan_user_id' => null,
            'aktif' => true,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        $referans->load([
            'olusturanUser',
            'santiye',
            'role'
        ]);

        return response()->json([
            'message' => 'Referans başarıyla oluşturuldu.',
            'data' => $referans
        ], 201);
    }
}