<?php

namespace App\Modules\Share\Provider;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BasePolymorphyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Log::info("BasePolymorphyServiceProvider::boot() started"); // Log awal

        $morphMap = [];

        $modulesPath = app_path('Modules');

        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);

            foreach ($modules as $module) {
                $moduleName = basename($module);
                $modelsPath = $module . '/Models';

                if (File::exists($modelsPath)) {
                    $modelFiles = File::files($modelsPath);

                    foreach ($modelFiles as $file) {
                        $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        $fqcn = "App\\Modules\\{$moduleName}\\Models\\{$className}";

                        if (class_exists($fqcn)) {
                            $key = Str::kebab($className); // Contoh: 'Product' menjadi 'product'
                            $morphMap[$key] = $fqcn;
                            \Log::debug("Adding to Morph Map", ['key' => $key, 'class' => $fqcn]); // Log setiap entri
                        }
                    }
                }
            }
        }

        \Log::info("Morph Map Built", $morphMap); // Log morph map sebelum enforce

        if (!empty($morphMap)) {
            \Log::info("About to call enforceMorphMap"); // Log sebelum enforce
            Relation::enforceMorphMap($morphMap);
            \Log::info("Called enforceMorphMap"); // Log setelah enforce

            // Jangan panggil setMorphMapInverse di sini karena macro ada di AppServiceProvider
            // Relation::setMorphMapInverse(array_flip($morphMap));

            // Cek morph map setelah enforce
            \Log::info("Current Morph Map after enforce", Relation::morphMap());
        } else {
             \Log::warning("Morph Map was empty, enforceMorphMap NOT called");
        }

        \Log::info("BasePolymorphyServiceProvider::boot() finished"); // Log akhir
    }
}
