<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Irsaliye extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'santiye_id',
        'irsaliye_fotografi',
        'malzeme_cinsi',
        'malzeme_adi',
        'malzeme_miktari',
        'tedarikci',
        'arac_plakasi',
        'tarih',
        'not',
    ];

    protected $casts = [
        'malzeme_miktari' => 'decimal:2',
        'tarih' => 'date',
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