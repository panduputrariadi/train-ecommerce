<?php

namespace App\Http\Admin\Resources\Detail;

use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $discountData = null;

        if ($this->is_discount && $this->discounts->isNotEmpty()) {
            $activeDiscount = $this->discounts
                ->where('expired_at', '>', now())
                ->sortByDesc('value')
                ->first();

            if ($activeDiscount) {
                $discountValue = $activeDiscount->value;
                $discountType = $activeDiscount->type;

                if ($discountType === 'percentage') {
                    $finalPrice = $this->price - (($discountValue / 100) * $this->price);
                } else {
                    $finalPrice = $this->price - $discountValue;
                }

                $discountData = [
                    'type' => $discountType,
                    'value' => (int) $discountValue,
                    'final_price' => max(0, (int) round($finalPrice)),
                ];
            }
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => (int) $this->price,
            'is_discount' => (bool) $this->is_discount,
            'category' => $this->category,
            'discount' => $discountData,
        ];
    }
}
