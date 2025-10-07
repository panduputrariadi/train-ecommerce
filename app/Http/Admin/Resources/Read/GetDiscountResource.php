<?php

namespace App\Http\Admin\Resources\Read;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDiscountResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'code' => $this->code,
            'value' => $this->value,
            'expired_at' => $this->expired_at,
            'products' => $this->products,
        ];
    }
}
