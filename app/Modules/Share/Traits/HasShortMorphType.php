<?php

namespace App\Modules\Share\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;

trait HasShortMorphType
{
    public function getMorphClass()
    {
        \Log::debug("HasShortMorphType::getMorphClass() called for: " . static::class); // Tambahkan logging untuk konfirmasi
        $alias = Relation::getMorphAlias(static::class);
        \Log::debug("Morph Alias for " . static::class . " is: " . ($alias ?? 'NULL (fallback to FQCN)')); // Logging alias
        return $alias ?? parent::getMorphClass(); // Pastikan fallback ke parent jika alias tidak ditemukan
    }
}
