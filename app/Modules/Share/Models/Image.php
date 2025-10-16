<?php

namespace App\Modules\Share\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path', 'disk', 'imageable_type', 'imageable_id'];

    /**
     * Get the parent imageable model (e.g., Product, User, etc.).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL to the image using Laravel Storage.
     */
    public function getUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        $disk = $this->disk ?: 'public';

        if (! Storage::disk($disk)->exists($this->path)) {
            return null;
        }

        return Storage::disk($disk)->url($this->path);
    }
}
