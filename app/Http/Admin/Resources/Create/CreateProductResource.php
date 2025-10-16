<?php

namespace App\Http\Admin\Resources\Create;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_discount' => $this->is_discount,
            'description' => $this->description,
            'category' => $this->category,
            // 'images' => $this->images->map(fn($img) => $img->url)
        ];
    }
}
