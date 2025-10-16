<?php

namespace App\Modules\Product\Providers;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Observers\CategoryObserver;
use App\Modules\Share\Provider\BaseModuleServiceProvider;

class ProductServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $observers = [
        Category::class => CategoryObserver::class,
    ];
}
