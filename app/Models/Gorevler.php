<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Santiye;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gorevler extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'title',
        'description',
    ];
        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function santiye(): HasMany
    {
        return $this->belongsTo(Santiye::class);
    }
}
