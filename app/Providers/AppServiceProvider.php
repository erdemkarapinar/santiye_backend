<?php

namespace App\Providers;
use App\Models\GunlukKayit;
use App\Observers\GunlukKayitObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        GunlukKayit::observe(GunlukKayitObserver::class);
    }
}
