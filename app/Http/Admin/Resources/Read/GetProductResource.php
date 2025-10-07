<?php

namespace App\Http\Admin\Resources\Read;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $discountData = null;
        $finalPrice = (int) $this->price;

        //function dalam model
        if ($this->is_discount && $this->discounts->isNotEmpty()) {
            $activeDiscount = $this->discounts->sortByDesc('value')->first();

            if ($activeDiscount) {
                $discountData = [
                    'type' => $activeDiscount->type,
                    'code' => $activeDiscount->code,
                    'value' => (int) $activeDiscount->value,
                    'expired_at' => $activeDiscount->expired_at,
                ];

                if ($activeDiscount->type === 'percentage') {
                    $finalPrice = $this->price - ($this->price * $activeDiscount->value / 100);
                } elseif ($activeDiscount->type === 'nominal') {
                    $finalPrice = max(0, $this->price - $activeDiscount->value);
                }
            }
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => (int) $this->price,
            'final_price' => (int) $finalPrice,
            'is_discount' => (bool) $this->is_discount,
            'discount' => $discountData,
        ];
    }
}
