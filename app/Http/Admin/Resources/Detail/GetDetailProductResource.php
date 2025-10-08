<?php

namespace App\Http\Admin\Resources\Detail;

use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => (int) $this->price,
            'final_price' => (int) $this->final_price,
            'is_discount' => (bool) $this->is_discount,
            'discount' => $this->active_discount,
        ];
    }
}
