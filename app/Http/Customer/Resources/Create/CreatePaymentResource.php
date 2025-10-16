<?php

namespace App\Http\Customer\Resources\Create;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatePaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'status' => $this->status,
        ];
    }
}
