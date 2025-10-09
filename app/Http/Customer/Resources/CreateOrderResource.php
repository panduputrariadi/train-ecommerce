<?php

namespace App\Http\Customer\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
