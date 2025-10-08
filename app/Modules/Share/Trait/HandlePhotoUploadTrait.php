<?php

namespace App\Modules\Share\Trait;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlePhotoUploadTrait
{
    /**
     * Upload a photo to the specified directory with a generated filename.
     *
     * @param UploadedFile|null $photo
     * @param string $directory  e.g. 'product-photo' or 'profile-photos'
     * @param int|string $entityId
     * @param string $nameForSlug  e.g. product name or user name
     * @param string|null $oldPhoto  existing photo path (optional, will be deleted)
     * @return string|null  stored path or null
     */
    public function uploadPhoto(?UploadedFile $photo, string $directory, int|string $entityId, string $nameForSlug, ?string $oldPhoto = null): ?string
    {
        if (! $photo instanceof UploadedFile) {
            return $oldPhoto;
        }

        if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        $slug = Str::slug($nameForSlug);
        $extension = $photo->getClientOriginalExtension();
        $filename = "{$entityId}_{$slug}.{$extension}";

        $photoPath = $photo->storeAs($directory, $filename, 'public');

        return $photoPath;
    }
}
