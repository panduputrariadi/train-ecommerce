<?php

namespace App\Http\Admin\Collections;

use App\Http\Admin\Resources\GetCategoryProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetCategoryProductCollection extends ResourceCollection
{
    public $collects = GetCategoryProductResource::class;
}
