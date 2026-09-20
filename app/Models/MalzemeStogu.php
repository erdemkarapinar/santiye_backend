<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MalzemeStogu extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'malzeme_adi',
        'birim',
        'miktar',
        'min_stok',
        'birim_fiyat',
        'not',
    ];

    protected $casts = [
        'miktar' => 'decimal:2',
        'min_stok' => 'decimal:2',
        'birim_fiyat' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function santiye()
    {
        return $this->belongsTo(Santiye::class);
    }
}
