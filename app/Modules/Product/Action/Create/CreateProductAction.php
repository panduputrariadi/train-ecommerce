<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Create\CreateProductRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateProductAction
{
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

        $photoPath = null;
        if ($dto->photo instanceof UploadedFile) {
            $nameSlug = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', strtolower($dto->name)));
            $filename = "{$product->id}_{$nameSlug}.{$dto->photo->getClientOriginalExtension()}";
            $photoPath = $dto->photo->storeAs('product-photo', $filename, 'public');
            $product->update([
                'photo' => $photoPath,
            ]);
        }

        return $product;
    }
}
