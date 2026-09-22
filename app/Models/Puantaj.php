<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Puantaj extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'olusturan_user_id',
        'tarih',
    ];

    protected $casts = [
        'tarih' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function santiye(): BelongsTo
    {
        return $this->belongsTo(Santiye::class);
    }

    public function olusturanUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'olusturan_user_id');
    }
}