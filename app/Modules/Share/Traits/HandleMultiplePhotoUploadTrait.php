<?php

namespace App\Modules\Share\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandleMultiplePhotoUploadTrait
{
    /**
     * Upload multiple photos and attach them to a polymorphic model.
     *
     * @param  array  $photos  Array of UploadedFile|string (base64)
     */
    public function uploadMultiplePhotos(array $photos, string $directory, Model $imageable, string $nameForSlug): void
    {
        $slug = Str::slug($nameForSlug);
        $entityId = $imageable->id;
        $imageableType = get_class($imageable);

        $imageRecords = [];

        foreach ($photos as $photo) {
            if (! $photo) {
                continue;
            }

            $filename = "{$entityId}_{$slug}_".Str::random(8);
            $path = null;

            if ($photo instanceof UploadedFile) {
                $extension = $photo->getClientOriginalExtension();
                $finalName = "{$filename}.{$extension}";
                $path = $photo->storeAs($directory, $finalName, 'public');
            } elseif (is_string($photo) && preg_match('/^data:image\/(\w+);base64,/', $photo, $type)) {
                $extension = strtolower($type[1]);
                $finalName = "{$filename}.{$extension}";
                $photoData = substr($photo, strpos($photo, ',') + 1);
                $photoData = base64_decode($photoData);
                $path = "{$directory}/{$finalName}";
                Storage::disk('public')->put($path, $photoData);
            }

            if ($path) {
                $imageRecords[] = [
                    'path' => $path,
                    'disk' => 'public',
                    'imageable_id' => $entityId,
                    'imageable_type' => $imageableType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($imageRecords)) {
            \DB::table('images')->insert($imageRecords);
        }
    }
}
