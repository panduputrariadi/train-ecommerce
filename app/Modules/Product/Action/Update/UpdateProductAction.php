<?php

namespace App\Modules\Product\Action\Update;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Update\UpdateProductRequest;
use App\Modules\Share\Trait\HandlePhotoUploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProductAction
{
    use HandlePhotoUploadTrait;

    /**
     * Update a product
     *
     * @param string $code
     * @param UpdateProductRequest $request
     * @return Product
     * @throws ModelNotFoundException
     */
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

        $photoPath = $this->uploadPhoto(
            $dto->photo,
            'product-photo',
            $product->id,
            $dto->name
        );

        if ($photoPath) {
            $product->update(['photo' => $photoPath]);
        }

        return $product;
    }
}
