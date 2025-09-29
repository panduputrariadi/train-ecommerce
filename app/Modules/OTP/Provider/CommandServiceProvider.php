<?php

namespace App\Modules\OTP\Provider;

use Illuminate\Support\ServiceProvider;
use App\Modules\OTP\Command\DeleteExpiredOtp;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register console commands
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeleteExpiredOtp::class,
            ]);
        }
    }
}
