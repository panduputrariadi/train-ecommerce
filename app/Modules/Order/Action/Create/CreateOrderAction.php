<?php

namespace App\Modules\Order\Action\Create;

use App\Modules\Order\DTOs\CreateOrderDto;
use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\DetailOrder;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    /**
     * Execute creation of a new order atomically.
     */
    public function execute(CreateOrderDto $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $user = Auth::user()->loadMissing(['profile']);

            $order = Order::createWithUser([
                'status'      => OrderStatus::PENDING,
                'sub_total'   => 0,
                'tax_amount'  => 0,
                'grand_total' => 0,
                'note'        => $dto->note,
            ], $user);

            $productIds = collect($dto->items)->pluck('product_id')->unique()->values();

            $products = Product::with(['discounts'])
                ->whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            $aggregatedItems = collect($dto->items)
                ->groupBy('product_id')
                ->map(fn ($items) => [
                    'product_id' => $items->first()['product_id'],
                    'quantity'   => $items->sum('quantity'),
                ])
                ->values();

            $detailData = [];
            $subTotal = 0;

            foreach ($aggregatedItems as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    continue;
                }

                $snapshot = $this->makeProductSnapshot($product);

                $unitPrice = (float) $snapshot['final_price'];
                $qty = (int) $item['quantity'];

                $totalPrice = $unitPrice * $qty;
                $discountId = $snapshot['active_discount']['id'] ?? null;
                $discountAmount = $this->calculateDiscountAmount(
                    $snapshot['price'],
                    $snapshot['active_discount'],
                    $qty
                );

                $finalTotal = $totalPrice;

                $detailData[] = [
                    'order_id'        => $order->id,
                    'product_id'      => $product->id,
                    'discount_id'     => $discountId,
                    'quantity'        => $qty,
                    'unit_price'      => $unitPrice,
                    'discount_amount' => $discountAmount,
                    'total_price'     => $finalTotal,
                    'product_data'    => json_encode($snapshot),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                $subTotal += $finalTotal;
            }

            if (!empty($detailData)) {
                DetailOrder::insert($detailData);
            }

            $taxRate = config('order.tax_rate', 10);
            $taxAmount = round($subTotal * $taxRate / 100, 2);
            $grandTotal = $subTotal + $taxAmount;

            $order->update([
                'sub_total'   => $subTotal,
                'tax_amount'  => $taxAmount,
                'grand_total' => $grandTotal,
            ]);

            return $order->fresh();
        });
    }

    /**
     * Snapshot product at order time.
     */
    private function makeProductSnapshot(Product $product): array
    {
        $activeDiscount = $product->active_discount;

        $snapshot = [
            'id'              => $product->id,
            'code'            => $product->code,
            'name'            => $product->name,
            'price'           => (float) $product->price,
            'final_price'     => (float) $product->final_price,
            'active_discount' => $activeDiscount,
            'photo'           => $product->photo,
        ];

        return $snapshot;
    }

    /**
     * Calculate discount amount — only for snapshot record purpose.
     */
    private function calculateDiscountAmount(float $basePrice, ?array $discount, int $qty): float
    {
        if (!$discount) return 0;

        $value = (float) ($discount['value'] ?? 0);

        return match ($discount['type']) {
            'percentage' => round($basePrice * ($value / 100) * $qty, 2),
            'nominal'    => round($value * $qty, 2),
            default      => 0,
        };
    }
}
