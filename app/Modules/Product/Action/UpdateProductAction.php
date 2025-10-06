<?php

namespace App\Modules\Product\Action;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\UpdateProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProductAction
{
    public function execute(string $code, UpdateProductRequest $request): Product
    {
        $product = Product::where('code', $code)->first();
        if (! $product) {
            throw new ModelNotFoundException('Product not found');
        }
        $dto = $request->validatedDto();

        $product->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'category_id' => $dto->categoryId,
        ]);

        if ($dto->photo) {
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }

            $slug = Str::slug($dto->name);
            $filename = "{$product->id}_{$slug}.".$dto->photo->getClientOriginalExtension();
            $photoPath = $dto->photo->storeAs('product-photo', $filename, 'public');

            $product->update(['photo' => $photoPath]);
        }

        return $product;
    }
}
