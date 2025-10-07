<?php

namespace App\Http\Admin\Resources\Create;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachDiscountToProductsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'success attach discount on products'
        ];
    }
}
