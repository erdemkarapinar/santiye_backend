<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KayitlarController extends Controller
{
    public function index()
    {
        $kayitlar = Kayit::with(['user', 'kayit'])
            ->latest()
            ->paginate(20);

        return response()->json($kayitlar);
    }
}
