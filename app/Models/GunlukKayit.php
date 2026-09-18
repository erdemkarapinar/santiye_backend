<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class GunlukKayit extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'weather',
        'temperature',
        'not',
        'ekipman',
        'toplam_ucret',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
