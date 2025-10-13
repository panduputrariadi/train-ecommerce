<?php

namespace App\Modules\Share\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlePhotoUploadTrait
{
    /**
     * Upload a photo (either UploadedFile or base64) to a directory with a generated filename.
     *
     * @param UploadedFile|string|null $photo
     * @param string $directory  e.g. 'product-photo' or 'profile-photos'
     * @param int|string $entityId
     * @param string $nameForSlug  e.g. product name or user name
     * @param string|null $oldPhoto  existing photo path (optional, will be deleted)
     * @return string|null  stored path or null
     */
    public function uploadPhoto(UploadedFile|string|null $photo, string $directory, int|string $entityId, string $nameForSlug, ?string $oldPhoto = null): ?string
    {
        if (! $photo) {
            return $oldPhoto;
        }

        if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        $slug = Str::slug($nameForSlug);
        $filename = "{$entityId}_{$slug}_" . Str::random(8);
        $path = null;

        if ($photo instanceof UploadedFile) {
            $extension = $photo->getClientOriginalExtension();
            $finalName = "{$filename}.{$extension}";
            $path = $photo->storeAs($directory, $finalName, 'public');
        }

        elseif (is_string($photo) && preg_match('/^data:image\/(\w+);base64,/', $photo, $type)) {
            $extension = strtolower($type[1]);
            $finalName = "{$filename}.{$extension}";
            $photoData = substr($photo, strpos($photo, ',') + 1);
            $photoData = base64_decode($photoData);
            $path = "{$directory}/{$finalName}";
            Storage::disk('public')->put($path, $photoData);
        }

        return $path;
    }
}
