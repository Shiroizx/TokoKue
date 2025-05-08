<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config as MidtransConfig;

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
    public function boot()
    {
        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = env('MIDTRANS_SERVER_KEY');
        MidtransConfig::$clientKey = env('MIDTRANS_CLIENT_KEY');
        MidtransConfig::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        MidtransConfig::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        MidtransConfig::$is3ds = env('MIDTRANS_IS_3DS', true);
    }
}