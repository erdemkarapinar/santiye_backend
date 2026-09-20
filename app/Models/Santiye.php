<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santiye extends Model
{
    protected $fillable = [
        'firma_adi',
        'santiye_adi',
        'baslangic_zamani',
        'bitis_zamani',
        'user_id',
        'location',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function hataliImalatlar()
    {
        return $this->hasMany(HataliImalat::class);
    }
}
