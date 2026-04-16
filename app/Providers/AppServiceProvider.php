<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Auto-detect Cloudflare tunnel or any HTTPS proxy
        if (config('app.force_https')
            || config('app.env') === 'production'
            || request()->header('X-Forwarded-Proto') === 'https'
            || str_contains(request()->header('Host', ''), 'trycloudflare.com')
        ) {
            URL::forceScheme('https');
        }
    }
}
