<?php

namespace App\Http\Controllers\Api;
use App\Models\Gorevler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GorevlerController extends Controller
{
     public function index()
    {
        $gorev = Gorevler::all();

        return response()->json([
            'success' => true,
            'message' => 'Görev başarıyla oluşturuldu.',
            'data' => $gorev,
        ], 200);
    }
        public function store(Request $request)
    {
        $validated = $request->validate([
            'santiye_id'   => 'required|exists:santiyes,id',
            'title'          => 'required|string|max:255',
            'description'      => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $gorev = Gorevler::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Günlük kayıt başarıyla oluşturuldu.',
            'data' => $gorev,
        ], 201);
    }
}
