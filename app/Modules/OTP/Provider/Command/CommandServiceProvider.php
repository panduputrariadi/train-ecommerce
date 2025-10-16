<?php

namespace App\Modules\OTP\Provider\Command;

use App\Modules\OTP\Command\DeleteExpiredOtp;
use Illuminate\Support\ServiceProvider;

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
