<?php

namespace App\Modules\Product\Action\Update;

use App\Modules\Product\DTOs\Update\UpdateProductDto;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Update\UpdateProductRequest;
use App\Modules\Share\Trait\HandlePhotoUploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateProductAction
{
    use HandlePhotoUploadTrait;


    /**
     * Execute the update of a product
     *
     * @param  Product  $product
     * @param  UpdateProductDto  $dto
     * @return Product
     */
    public function execute(Product $product, UpdateProductDto $dto): Product
    {

        $product->update([
            'name' => $dto->name ?? $product->name,
            'description' => $dto->description ?? $product->description,
            'price' => $dto->price ?? $product->price,
            'stock' => $dto->stock ?? $product->stock,
            'category_id' => $dto->categoryId ?? $product->category_id,
        ]);

        $photoPath = $this->uploadPhoto(
            $dto->photo,
            'product-photo',
            $product->id,
            $dto->name ?? $product->name
        );

        if ($photoPath) {
            $product->update(['photo' => $photoPath]);
        }

        return $product;
    }
}
