<?php

namespace App\Modules\Share\Provider;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected array $observers = [];

    public function boot(): void
    {
        foreach ($this->observers as $model => $observer) {
            if (class_exists($model) && class_exists($observer)) {
                $model::observe($observer);
            }
        }
    }
}
