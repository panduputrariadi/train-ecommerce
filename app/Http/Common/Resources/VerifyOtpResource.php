<?php

namespace App\Http\Common\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifyOtpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'email' => $this->email,
            'updated_at' => $this->updated_at,
            'verified_at' => $this->verified_at,
        ];
    }
}
