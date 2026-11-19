<?php

namespace App\Providers;

use App\Services\Paystack;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Paystack::class, fn () => new Paystack(
            config('services.paystack.secret_key'),
            config('services.paystack.base_url'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
