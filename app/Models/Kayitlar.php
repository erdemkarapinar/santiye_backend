<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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