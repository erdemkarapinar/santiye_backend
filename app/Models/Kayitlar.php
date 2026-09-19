<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kayitlar extends Model
{
    protected $fillable = [
        'user_id',
        'kayit_type',
        'kayit_id',
        'aciklama',
    ];

    public function kayit()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}