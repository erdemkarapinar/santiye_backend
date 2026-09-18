<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GunlukKayit;
use App\Models\Santiye;
use Illuminate\Http\JsonResponse;

class RaporController extends Controller
{
    public function index(): JsonResponse
    {
        // Toplam şantiye sayısı
        $santiyeSayisi = Santiye::count();

        // Toplam günlük kayıt sayısı
        $gunlukKayitSayisi = GunlukKayit::count();

        // Toplam harcama
        $toplamHarcama = GunlukKayit::sum('toplam_ucret');

        // Kullanılan ekipmanlar
        $ekipmanlar = GunlukKayit::whereNotNull('ekipman')
            ->where('ekipman', '!=', '')
            ->pluck('ekipman');

        return response()->json([
            'santiye_sayisi' => $santiyeSayisi,
            'gunluk_kayit_sayisi' => $gunlukKayitSayisi,
            'toplam_harcama' => $toplamHarcama,
            'ekipmanlar' => $ekipmanlar,
        ]);
    }
}