<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OtpRateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $register = require app_path('Modules/OTP/Provider/Limiter/index.php');
        $register();
    }
}
