<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\CreateProductDto;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;

class CreateProductAction
{
    use HandlePhotoUploadTrait;

    /**
     * Execute the creation of a new product
     *
     * @param  CreateProductDto  $dto
     * @return Product
     */
    public function execute(CreateProductDto $dto): Product
    {
        $product = Product::createWithCode([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'is_discount' => $dto->isDiscount,
            'photo' => null,
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
