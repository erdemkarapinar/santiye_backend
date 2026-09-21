<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referans extends Model
{
    use HasFactory;

    protected $table = 'referanslar';

    protected $fillable = [
        'olusturan_user_id',
        'santiye_id',
        'role_id',
        'kod',
        'kullanildi_mi',
        'kullanan_user_id',
        'aktif',
        'expires_at',
    ];

    protected $casts = [
        'kullanildi_mi' => 'boolean',
        'aktif' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function olusturanUser()
    {
        return $this->belongsTo(User::class, 'olusturan_user_id');
    }

    public function kullananUser()
    {
        return $this->belongsTo(User::class, 'kullanan_user_id');
    }

    public function santiye()
    {
        return $this->belongsTo(Santiye::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public static function generateCode(): string
    {
        do {
            $code = 'SNT-' .
                strtoupper(\Illuminate\Support\Str::random(4)) . '-' .
                strtoupper(\Illuminate\Support\Str::random(4));
        } while (self::where('kod', $code)->exists());

        return $code;
    }
}