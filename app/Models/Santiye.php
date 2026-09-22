<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santiye extends Model
{
    protected $fillable = [
        'firma_adi',
        'santiye_adi',
        'baslangic_zamani',
        'bitis_zamani',
        'user_id',
        'location',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function hataliImalatlar()
    {
        return $this->hasMany(HataliImalat::class);
    }
    public function malzemeler()
    {
        return $this->hasMany(MalzemeStogu::class);
    }
        public function betonFisleri()
    {
        return $this->hasMany(BetonFisi::class);
    }
    public function referanslar()
    {
        return $this->hasMany(Referans::class);
    }
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'santiye_user'
        )->withTimestamps();
    }
    public function puantajlar()
    {
        return $this->hasMany(Puantaj::class);
    }
    public function metrajlar()
    {
        return $this->hasMany(Metraj::class);
    }
}
