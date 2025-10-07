<?php

namespace App\Http\Admin\Collections;

use App\Http\Admin\Resources\Read\GetDiscountResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetDiscountCollection extends ResourceCollection
{
    public $collects = GetDiscountResource::class;
}
