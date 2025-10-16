<?php

namespace App\Http\Customer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetOrderResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $this->status,
            'sub_total' => $this->sub_total,
            'tax_amount' => $this->tax_amount,
            'grand_total' => $this->grand_total,
            'note' => $this->note,
            'details' => $this->details,
        ];
    }
}
