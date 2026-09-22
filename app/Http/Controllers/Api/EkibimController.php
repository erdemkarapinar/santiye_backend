<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EkibimController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Giriş yapan kullanıcının oluşturduğu şantiyeler
        |--------------------------------------------------------------------------
        */

        $santiyeler = $user->santiye()
            ->with([
                'users.role'
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Şantiyeler ve ekip üyeleri
        |--------------------------------------------------------------------------
        */

        $data = $santiyeler->map(function ($santiye) {

            return [
                'santiye_id' => $santiye->id,
                'santiye_adi' => $santiye->santiye_adi,
                'firma_adi' => $santiye->firma_adi,

                'ekip' => $santiye->users->map(function ($personel) {

                    return [
                        'user_id' => $personel->id,
                        'name' => $personel->name,
                        'email' => $personel->email,

                        'role' => $personel->role
                            ? $personel->role->name
                            : null,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Ekibiniz başarıyla getirildi.',
            'data' => $data,
        ]);
    }
        /**
     * Ekipteki bir kullanıcıyı,
     * giriş yapan kullanıcının oluşturduğu bir şantiyeye atar.
     */
    public function santiyeAta(Request $request, User $user)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',
        ]);

        $girisYapanUser = $request->user();

        /*
        |--------------------------------------------------------------------------
        | 1. Atanacak kullanıcı gerçekten bu kişinin ekibinde mi?
        |--------------------------------------------------------------------------
        */

        $ekipUyesiMi = $girisYapanUser
            ->olusturduguReferanslar()
            ->where('kullanan_user_id', $user->id)
            ->exists();

        if (!$ekipUyesiMi) {
            return response()->json([
                'message' => 'Bu kullanıcı sizin ekibinizde bulunmuyor.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Şantiye gerçekten giriş yapan kullanıcı tarafından mı oluşturuldu?
        |--------------------------------------------------------------------------
        */

        $santiye = $girisYapanUser
            ->santiye()
            ->where('id', $validated['santiye_id'])
            ->first();

        if (!$santiye) {
            return response()->json([
                'message' => 'Bu şantiye size ait değil.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Kullanıcıyı şantiyeye ekle
        |--------------------------------------------------------------------------
        |
        | syncWithoutDetaching sayesinde:
        |
        | - Kullanıcı daha önce bu şantiyeye atanmışsa tekrar eklenmez.
        | - Aynı kullanıcı başka şantiyelere de atanabilir.
        |
        */

        $santiye->users()->syncWithoutDetaching([
            $user->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. Güncel kullanıcı bilgilerini getir
        |--------------------------------------------------------------------------
        */

        $user->load([
            'role',
            'santiyeler'
        ]);

        return response()->json([
            'message' => 'Ekip üyesi şantiyeye başarıyla atandı.',
            'data' => [
                'user' => $user,
                'santiye' => $santiye,
            ]
        ], 200);
    }
    public function personelinSantiyeleri(Request $request, User $user)
    {
        $girisYapanUser = $request->user();

        // Bu personel giriş yapan kullanıcının ekibinde mi?
        $ekipUyesiMi = $girisYapanUser
            ->olusturduguReferanslar()
            ->where('kullanan_user_id', $user->id)
            ->exists();

        if (!$ekipUyesiMi) {
            return response()->json([
                'message' => 'Bu kullanıcı sizin ekibinizde bulunmuyor.'
            ], 403);
        }

        // Personelin bağlı olduğu şantiyeleri getir
        $santiyeler = $user->santiyeler()
            ->with('user')
            ->get();

        return response()->json([
            'message' => 'Personelin görev yaptığı şantiyeler başarıyla getirildi.',
            'data' => [
                'personel' => $user->load('role'),
                'santiyeler' => $santiyeler,
            ]
        ], 200);
    }
}