<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\CreateProductDto;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Traits\HandleMultiplePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;

class CreateProductAction
{
    use HandleMultiplePhotoUploadTrait;

    /**
     * Execute the creation of a new product
     */
    public function execute(CreateProductDto $dto): Product
    {
        $product = Product::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'is_discount' => $dto->isDiscount,
            'category_id' => $dto->categoryId,
            'created_by' => Auth::id(),
        ]);

        if (! empty($dto->photos)) {
            $this->uploadMultiplePhotos(
                photos: $dto->photos,
                directory: 'product-photos',
                imageable: $product,
                nameForSlug: $dto->name
            );
        }

        return $product;
    }
}
