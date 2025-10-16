<?php

namespace App\Http\Admin\Collections;

use App\Http\Admin\Resources\Read\GetCustomerResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetCustomerCollection extends ResourceCollection
{
    public $collects = GetCustomerResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
