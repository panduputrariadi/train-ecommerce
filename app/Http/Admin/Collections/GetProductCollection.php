<?php

namespace App\Http\Admin\Collections;

use App\Http\Admin\Resources\GetProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetProductCollection extends ResourceCollection
{
    public $collects = GetProductResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
