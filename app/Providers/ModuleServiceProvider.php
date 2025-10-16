<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

/**
 *
 */
class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $modulesPath = app_path('Modules');

        foreach (File::directories($modulesPath) as $modulePath) {
            $providerPath = $modulePath . '/Providers';

            if (File::isDirectory($providerPath)) {
                foreach (File::files($providerPath) as $file) {
                    $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $namespace = 'App\\Modules\\' . basename($modulePath) . '\\Providers\\' . $className;

                    if (class_exists($namespace)) {
                        $this->app->register($namespace);
                    }
                }
            }
        }
    }
}
