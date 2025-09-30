<?php

namespace App\Http\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateRegisterUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->profile->name,
            'photo' => $this->profile->photo ? url('storage/' . $this->profile->photo) : null,
        ];
    }
}
