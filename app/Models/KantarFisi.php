<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Santiye;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KantarFisi extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'fis_fotografi',
        'urun_cinsi',
        'tedarikci',
        'giris_kg',
        'cikis_kg',
        'net_agirlik',
        'tarih',
        'not',
    ];

    protected $casts = [
        'giris_kg' => 'decimal:2',
        'cikis_kg' => 'decimal:2',
        'net_agirlik' => 'decimal:2',
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
