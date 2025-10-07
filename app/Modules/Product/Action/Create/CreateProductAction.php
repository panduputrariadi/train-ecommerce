<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Create\CreateProductRequest;
use App\Modules\Share\Trait\HandlePhotoUploadTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateProductAction
{

    use HandlePhotoUploadTrait;

    /**
     * Create a new product
     *
     * @param CreateProductRequest $request
     * @return Product
     *
     */
    public function execute(CreateProductRequest $request): Product
    {
        $dto = $request->validatedDto();
        $product = Product::create([
            'code' => Str::uuid(),
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
