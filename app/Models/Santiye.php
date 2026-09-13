<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santiye extends Model
{
    protected $fillable = [
        'firma_adi',
        'santiye_adi',
        'baslangic_zamani',
        'bitis_zamani',
    ];
}
