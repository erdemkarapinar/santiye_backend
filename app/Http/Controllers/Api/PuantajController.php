<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GunlukKayit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuantajController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Tarih filtresi
        |--------------------------------------------------------------------------
        */

        $gun = $request->query('gun', 'all');

        if (!in_array($gun, ['7', '30', 'all'], true)) {
            return response()->json([
                'message' => 'gun parametresi 7, 30 veya all olmalıdır.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Kullanıcının oluşturduğu şantiyeler
        |--------------------------------------------------------------------------
        */

        $santiyeIds = $user->santiye()
            ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Günlük kayıtlar
        |--------------------------------------------------------------------------
        */

        $gunlukKayitQuery = GunlukKayit::query()
            ->whereIn('santiye_id', $santiyeIds);

        if ($gun !== 'all') {
            $baslangicTarihi = now()
                ->subDays((int) $gun - 1)
                ->startOfDay();

            $gunlukKayitQuery->where(
                'created_at',
                '>=',
                $baslangicTarihi
            );
        }

        $gunlukKayitlari = $gunlukKayitQuery
            ->with([
                'personeller.role',
                'santiye'
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Günlük kayıtlarda bulunan personeller
        |--------------------------------------------------------------------------
        |
        | Aynı kişi birden fazla günlük kayıtta varsa
        | sadece 1 kez sayıyoruz.
        |
        */

        $gunlukPersoneller = $gunlukKayitlari
            ->flatMap(function ($kayit) {
                return $kayit->personeller;
            })
            ->unique('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Şantiyelere atanmış personeller
        |--------------------------------------------------------------------------
        */

        $atanmisPersoneller = User::query()
            ->whereHas('santiyeler', function ($query) use ($santiyeIds) {
                $query->whereIn('santiyes.id', $santiyeIds);
            })
            ->with('role')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Günlük kayda giren + şantiyeye atanmış personeller
        |--------------------------------------------------------------------------
        |
        | Aynı personel hem atanmış hem günlük kayda eklenmişse
        | toplamda bir kez sayılır.
        |
        */

        $tumPersoneller = $atanmisPersoneller
            ->concat($gunlukPersoneller)
            ->unique('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Meslek dağılımı
        |--------------------------------------------------------------------------
        */

        $meslekDagilimi = $tumPersoneller
            ->groupBy(function ($personel) {
                return $personel->role?->name ?? 'Meslek belirtilmemiş';
            })
            ->map(function ($personeller, $meslek) {
                return [
                    'meslek' => $meslek,
                    'sayi' => $personeller->unique('id')->count(),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Şantiye bazlı personel sayısı
        |--------------------------------------------------------------------------
        */

        $santiyeler = $user->santiye()
            ->with('users.role')
            ->get();

        $santiyeDagilimi = $santiyeler
            ->map(function ($santiye) use ($gunlukKayitlari) {

                /*
                | Bu şantiyenin atanmış personelleri
                */
                $atanmis = $santiye->users
                    ->unique('id');

                /*
                | Bu şantiyenin seçilen tarih aralığındaki
                | günlük kayıtlarda bulunan personelleri
                */
                $gunluk = $gunlukKayitlari
                    ->where('santiye_id', $santiye->id)
                    ->flatMap(function ($kayit) {
                        return $kayit->personeller;
                    })
                    ->unique('id');

                /*
                | İki listeyi birleştirip aynı kişiyi bir kez say
                */
                $personeller = $atanmis
                    ->concat($gunluk)
                    ->unique('id')
                    ->values();

                return [
                    'santiye_id' => $santiye->id,
                    'santiye_adi' => $santiye->santiye_adi,
                    'personel_sayisi' => $personeller->count(),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Ayrı istatistikler
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Puantaj başarıyla getirildi.',

            'filtre' => [
                'gun' => $gun,
                'baslangic' => $gun === 'all'
                    ? null
                    : $baslangicTarihi->toDateString(),
                'bitis' => now()->toDateString(),
            ],

            'data' => [

                // Şantiyelere atanmış toplam benzersiz personel
                'atanmis_personel_sayisi' => $atanmisPersoneller
                    ->unique('id')
                    ->count(),

                // Seçilen dönemde günlük kayıtlara girilmiş
                // toplam benzersiz personel
                'gunluk_kayit_personel_sayisi' => $gunlukPersoneller
                    ->unique('id')
                    ->count(),

                // Atanmış + günlük kayda giren
                // benzersiz toplam personel
                'toplam_personel' => $tumPersoneller
                    ->unique('id')
                    ->count(),

                'meslek_dagilimi' => $meslekDagilimi,

                'santiye_dagilimi' => $santiyeDagilimi,
            ],
        ], 200);
    }
}