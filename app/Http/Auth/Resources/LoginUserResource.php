<?php

namespace App\Http\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserResource extends JsonResource
{
    protected string $token;

    public function __construct($resource, string $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->resource,
            'token' => $this->token,
        ];
    }
}
