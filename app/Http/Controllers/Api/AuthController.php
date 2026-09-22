<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Normal kayıt için
            'role_id' => ['nullable', 'exists:roles,id'],

            // Referanslı kayıt için
            'referans_kodu' => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Referans kodu gönderilmiş mi?
        |--------------------------------------------------------------------------
        */

        $referans = null;

        if (!empty($validated['referans_kodu'])) {

            $referans = \App\Models\Referans::where(
                'kod',
                $validated['referans_kodu']
            )->first();

            if (!$referans) {
                return response()->json([
                    'message' => 'Geçersiz referans kodu.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Referans aktif mi?
            |--------------------------------------------------------------------------
            */

            if (!$referans->aktif) {
                return response()->json([
                    'message' => 'Bu referans kodu aktif değil.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Referans daha önce kullanılmış mı?
            |--------------------------------------------------------------------------
            */

            if ($referans->kullanildi_mi) {
                return response()->json([
                    'message' => 'Bu referans kodu daha önce kullanılmış.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Referansın süresi dolmuş mu?
            |--------------------------------------------------------------------------
            */

            if (
                $referans->expires_at &&
                $referans->expires_at->isPast()
            ) {
                return response()->json([
                    'message' => 'Bu referans kodunun süresi dolmuş.'
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normal kayıt / Referanslı kayıt
        |--------------------------------------------------------------------------
        */

        if ($referans) {

            // Referanslı kayıt:
            // Rol referanstan gelir.
            $roleId = $referans->role_id;

        } else {

            // Normal kayıt:
            // Kullanıcının seçtiği rol kullanılır.
            if (empty($validated['role_id'])) {
                return response()->json([
                    'message' => 'Normal kayıt için role_id gereklidir.'
                ], 422);
            }

            $roleId = $validated['role_id'];
        }

        /*
        |--------------------------------------------------------------------------
        | User oluştur
        |--------------------------------------------------------------------------
        */

        $user = \Illuminate\Support\Facades\DB::transaction(function () use (
            $validated,
            $roleId,
            $referans
        ) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $roleId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Referanslı kayıt ise şantiyeye otomatik ekle
            |--------------------------------------------------------------------------
            */

            if ($referans) {

                $referans->santiye
                    ->users()
                    ->syncWithoutDetaching([
                        $user->id
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Referansı kullanıldı olarak işaretle
                |--------------------------------------------------------------------------
                */

                $referans->update([
                    'kullanildi_mi' => true,
                    'kullanan_user_id' => $user->id,
                ]);
            }

            return $user;
        });

        /*
        |--------------------------------------------------------------------------
        | Token oluştur
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('mobile-token')
            ->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | Kullanıcı ilişkilerini yükle
        |--------------------------------------------------------------------------
        */

        $user->load([
            'role',
            'santiyeler'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => $referans
                ? 'Kayıt başarılı. Referans ile şantiyeye eklendiniz.'
                : 'Kayıt başarılı.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['E-posta veya şifre hatalı.'],
            ]);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'message' => 'Giriş başarılı.',
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Çıkış başarılı.',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('role'),
        ]);
    }
}
