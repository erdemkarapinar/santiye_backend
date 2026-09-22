<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Metraj extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'metraj_tipi',

        'uzunluk',
        'genislik',
        'kalinlik',
        'yukseklik',

        'kapi_sayisi',
        'pencere_sayisi',

        'fire_orani',

        'demir_cap',
        'demir_adet',
        'demir_boy',
        'demir_birim_agirlik',

        'sonuc',
        'birim',
    ];

    protected $casts = [
        'uzunluk' => 'decimal:3',
        'genislik' => 'decimal:3',
        'kalinlik' => 'decimal:3',
        'yukseklik' => 'decimal:3',
        'fire_orani' => 'decimal:2',
        'demir_cap' => 'decimal:3',
        'demir_boy' => 'decimal:3',
        'demir_birim_agirlik' => 'decimal:3',
        'sonuc' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function santiye(): BelongsTo
    {
        return $this->belongsTo(Santiye::class);
    }
}