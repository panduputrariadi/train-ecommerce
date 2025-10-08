<?php

namespace App\Http\Admin\Resources\Create;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateDiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
        ];
    }
}
