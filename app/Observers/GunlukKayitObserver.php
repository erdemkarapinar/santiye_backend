<?php

namespace App\Observers;

use App\Models\GunlukKayit;
use App\Models\Kayitlar;

class GunlukKayitObserver
{
    /**
     * Handle the GunlukKayit "created" event.
     */
    public function created(GunlukKayit $gunlukKayit): void
    {
         Kayitlar::create([
            'user_id' => $gunlukKayit->user_id,
            'kayit_type' => GunlukKayit::class,
            'kayit_id' => $gunlukKayit->id,
            'aciklama' => 'Günlük kayıt oluşturuldu',
        ]);
    }

    /**
     * Handle the GunlukKayit "updated" event.
     */
    public function updated(GunlukKayit $gunlukKayit): void
    {
        //
    }

    /**
     * Handle the GunlukKayit "deleted" event.
     */
    public function deleted(GunlukKayit $gunlukKayit): void
    {
        //
    }

    /**
     * Handle the GunlukKayit "restored" event.
     */
    public function restored(GunlukKayit $gunlukKayit): void
    {
        //
    }

    /**
     * Handle the GunlukKayit "force deleted" event.
     */
    public function forceDeleted(GunlukKayit $gunlukKayit): void
    {
        //
    }
}
