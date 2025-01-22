<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        // Retrieve the locale from the cookie, defaulting to 'en' if not set
        $locale = Cookie::get('locale', 'en'); // Default to 'en' if no cookie is found
        App::setLocale($locale); // Set the application's locale globally
      
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::except([
            'payment-test',
            'iys-webhook'
        ]);
        
    }
}
