<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BetonFisi extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'beton_fotosu',
        'irsaliye_fotosu',
        'beton_sinifi',
        'tedarikci',
        'miktar',
        'arac_plakasi',
        'tarih',
        'not',
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
