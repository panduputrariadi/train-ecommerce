<?php

namespace App\Http\Customer\Collection;

use App\Http\Customer\Resources\GetOrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetOrderCollection extends ResourceCollection
{
    public $collects = GetOrderResource::class;
}
