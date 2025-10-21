<?php

namespace App\Http\Admin\Resources\Read;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => (float) $this->price,
            'final_price' => (float) $this->final_price,
            'is_discount' => (bool) $this->is_discount,
            'discount' => $this->active_discount,
        ];
    }
}
