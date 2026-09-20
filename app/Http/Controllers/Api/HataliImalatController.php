<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HataliImalat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HataliImalatController extends Controller
{       public function index()
        {
            $hataliImalatlar = HataliImalat::with([
                'user',
                'santiye'
            ])->latest()->get();

            return response()->json([
                'message' => 'Hatalı imalat kayıtları başarıyla getirildi.',
                'data' => $hataliImalatlar
            ]);
        }
        public function store(Request $request)
        {
            $validated = $request->validate([
                'santiye_id' => ['required', 'exists:santiyes,id'],

                'baslik' => ['required', 'string', 'max:255'],

                'aciklama' => ['nullable', 'string'],

                'konum' => ['nullable', 'string', 'max:255'],

                'durum' => [
                    'required',
                    Rule::in([
                        'acik',
                        'devam_ediyor',
                        'kapali',
                    ]),
                ],

                'fotograf' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:10240',
                ],
            ]);

            $validated['user_id'] = $request->user()->id;

            if ($request->hasFile('fotograf')) {
                $validated['fotograf'] = $request
                    ->file('fotograf')
                    ->store('hatali-imalatlar', 'public');
            }

            $hataliImalat = HataliImalat::create($validated);

            return response()->json([
                'message' => 'Hatalı imalat kaydı başarıyla oluşturuldu.',
                'data' => $hataliImalat->load([
                    'user',
                    'santiye',
                ]),
            ], 201);
        }
}
