<?php

namespace App\Http\Admin\Resources\Create;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
