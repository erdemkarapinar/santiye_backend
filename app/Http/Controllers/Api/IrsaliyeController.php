<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Irsaliye;
use App\Models\MalzemeStogu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IrsaliyeController extends Controller
{
    public function index(Request $request)
    {
        $irsaliyeler = Irsaliye::with([
            'user',
            'santiye'
        ])
        ->latest()
        ->get();

        return response()->json([
            'message' => 'İrsaliyeler başarıyla getirildi.',
            'data' => $irsaliyeler
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id' => 'required|exists:santiyes,id',

            'irsaliye_fotografi' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'malzeme_cinsi' => 'required|string|max:255',

            'malzeme_adi' => 'required|string|max:255',

            'malzeme_miktari' => 'required|numeric|min:0',

            'tedarikci' => 'required|string|max:255',

            'arac_plakasi' => 'required|string|max:20',

            'tarih' => 'required|date',

            'not' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            /*
             * Fotoğraf
             */
            $fotografYolu = null;

            if ($request->hasFile('irsaliye_fotografi')) {
                $fotografYolu = $request
                    ->file('irsaliye_fotografi')
                    ->store('irsaliyeler', 'public');
            }

            /*
             * İrsaliye oluştur
             */
            $irsaliye = Irsaliye::create([
                'user_id' => $request->user()->id,

                'santiye_id' => $validated['santiye_id'],

                'irsaliye_fotografi' => $fotografYolu,

                'malzeme_cinsi' => $validated['malzeme_cinsi'],

                'malzeme_adi' => $validated['malzeme_adi'],

                'malzeme_miktari' => $validated['malzeme_miktari'],

                'tedarikci' => $validated['tedarikci'],

                'arac_plakasi' => $validated['arac_plakasi'],

                'tarih' => $validated['tarih'],

                'not' => $validated['not'] ?? null,
            ]);

            /*
             * Aynı şantiyede aynı malzeme var mı?
             */
            $stok = MalzemeStogu::where('santiye_id', $validated['santiye_id'])
                ->where('malzeme_adi', $validated['malzeme_adi'])
                ->where('aktif', true)
                ->first();

            if ($stok) {

                /*
                 * Mevcut stok miktarına ekle
                 */
                $stok->miktar =
                    $stok->miktar + $validated['malzeme_miktari'];

                $stok->save();

                $stokIslemi = 'mevcut_stok_guncellendi';

            } else {

                /*
                 * Böyle bir malzeme yoksa yeni stok oluştur
                 */
                $stok = MalzemeStogu::create([
                    'user_id' => $request->user()->id,
                    'santiye_id' => $validated['santiye_id'],
                    'malzeme_adi' => $validated['malzeme_adi'],
                    'birim' => 'adet',
                    'miktar' => $validated['malzeme_miktari'],
                    'min_stok' => 0,
                    'birim_fiyat' => 0,
                    'not' => 'İrsaliye üzerinden otomatik oluşturuldu.',
                ]);

                $stokIslemi = 'yeni_stok_olusturuldu';
            }

            DB::commit();

            return response()->json([
                'message' => 'İrsaliye başarıyla oluşturuldu.',
                'data' => [
                    'irsaliye' => $irsaliye->load([
                        'user',
                        'santiye'
                    ]),

                    'stok' => $stok,

                    'stok_islemi' => $stokIslemi,
                ]
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'İrsaliye oluşturulurken hata oluştu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}