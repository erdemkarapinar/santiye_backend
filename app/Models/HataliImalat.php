<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class HataliImalat extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'baslik',
        'aciklama',
        'konum',
        'durum',
        'fotograf',
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
