<?php

namespace App\Http\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $role = $this->roles->first();

        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->profile?->name ?? 'No Name',
                'email' => $this->email,
                'photo' => $this->profile?->photo ?? null,
                'role' => $role ? [
                    'slug' => $role->slug,
                    'name' => $role->name,
                ] : null,
                'addresses' => $this->addresses->map(function ($address) {
                    return [
                        'id' => $address->id,
                        'address' => $address->address,
                        'city' => $address->city,
                        'state' => $address->state,
                        'zip_code' => $address->zip_code,
                        'is_default' => $address->is_default,
                    ];
                }),
            ],
        ];
    }
}
