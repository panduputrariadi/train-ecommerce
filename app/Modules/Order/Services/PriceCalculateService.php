<?php

namespace App\Modules\Order\Services;

use App\Modules\Product\Models\Product;

class PriceCalculateService
{
    /**
     *
     * @param  array<int, array{product: Product, quantity: int}>  $items
     * @return float
     */
    public function calculateSubTotal(array $items): float
    {
        return collect($items)->sum(function ($item) {
            $product = $item['product'];
            $qty = $item['quantity'];
            $unitPrice = $this->getFinalUnitPrice($product);
            return $unitPrice * $qty;
        });
    }

    /**
     *
     * @param  float  $basePrice
     * @param  array|null  $discount
     * @param  int  $qty
     * @return float
     */
    public function calculateDiscountAmount(float $basePrice, ?array $discount, int $qty): float
    {
        if (! $discount) {
            return 0;
        }

        $value = (float) ($discount['value'] ?? 0);

        return match ($discount['type'] ?? null) {
            'percentage' => round($basePrice * ($value / 100) * $qty, 2),
            'nominal' => round($value * $qty, 2),
            default => 0,
        };
    }

    /**
     *
     * @param  float  $subTotal
     * @param  float|null  $taxRate
     * @return array{tax_amount: float, grand_total: float}
     */
    public function calculateTotals(float $subTotal, ?float $taxRate = null): array
    {
        $taxRate = $taxRate ?? (float) config('order.tax_rate', 10);
        $taxAmount = round($subTotal * $taxRate / 100, 2);
        $grandTotal = $subTotal + $taxAmount;

        return [
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     *
     * @param  Product  $product
     * @return float
     */
    public function getFinalUnitPrice(Product $product): float
    {
        return (float) ($product->final_price ?? $product->price);
    }
}
