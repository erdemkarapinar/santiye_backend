<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Santiye;
use App\Models\GunlukKayit;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function santiye(): HasMany
    {
        return $this->hasMany(Santiye::class);
    }
    public function kayit(): HasMany
    {
        return $this->hasMany(GunlukKayit::class);
    }
        public function gorev(): HasMany
    {
        return $this->hasMany(GunlukKayit::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
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
    public function olusturduguReferanslar()
    {
        return $this->hasMany(Referans::class, 'olusturan_user_id');
    }

    public function kullandigiReferans()
    {
        return $this->hasOne(Referans::class, 'kullanan_user_id');
    }
    public function santiyeler()
    {
        return $this->belongsToMany(
            Santiye::class,
            'santiye_user'
        )->withTimestamps();
    }
}
