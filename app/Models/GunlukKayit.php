<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kayitlar;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GunlukKayit extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'weather',
        'temperature',
        'not',
        'ekipman',
        'harcama_kategori',
        'harcama_aciklama',
        'harcama_tutar',
        'toplam_ucret',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function kayit()
    {
        return $this->morphOne(Kayitlar::class, 'kayit');
    }
}
