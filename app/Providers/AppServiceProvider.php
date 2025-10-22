<?php

namespace App\Providers;

use App\Modules\Order\Models\Order;
use App\Modules\Order\Policies\OrderPolicy;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Policies\PaymentPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

// use Laravel\Sanctum\Sanctum;

static $morphMapInverse = [];
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        // Register the macro to set the inverse map
        Relation::macro('setMorphMapInverse', function (array $map) use (&$morphMapInverse) { // Pass by reference to modify the static variable
            $morphMapInverse = $map;
        });

        // Register the macro to get the inverse map
        Relation::macro('getMorphMapInverse', function () use (&$morphMapInverse) { // Pass by reference to access the static variable
            return $morphMapInverse; // Return the stored inverse map
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sanctum::useTokenAuthentication();
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
