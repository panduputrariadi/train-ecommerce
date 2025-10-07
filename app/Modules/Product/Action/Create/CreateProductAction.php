<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\CreateProductDto;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Create\CreateProductRequest;
use App\Modules\Share\Helper\CodeGenerator;
use App\Modules\Share\Trait\HandlePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;

class CreateProductAction
{
    use HandlePhotoUploadTrait;

    /**
     * Create a new product
     *
     * @param  CreateProductRequest  $request
     */
    public function execute(CreateProductDto $dto): Product
    {
        $product = Product::create([
            'code' => CodeGenerator::generate('products', 'PRD', $dto->name),
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'is_discount' => $dto->isDiscount,
            'photo' => null,
            'category_id' => $dto->categoryId,
            'created_by' => Auth::id(),
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
