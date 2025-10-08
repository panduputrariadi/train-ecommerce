<?php

namespace App\Http\Admin\Resources\Read;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCustomerResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'photo' => $this->photo,
            'phone' => $this->phone
        ];
    }
}
