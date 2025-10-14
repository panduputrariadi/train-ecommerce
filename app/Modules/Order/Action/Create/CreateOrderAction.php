<?php

namespace App\Modules\Order\Action\Create;

use App\Modules\Order\DTOs\CreateOrderDto;
use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\DetailOrder;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    public function execute(CreateOrderDto $dto): Order
    {
        $user = Auth::user()->loadMissing(['profile']);

        $order = $this->createOrder($dto, $user);

        $products = $this->getProducts($dto);
        $aggregatedItems = $this->aggregateItems($dto);

        [$detailData, $subTotal] = $this->buildDetailOrders($order, $aggregatedItems, $products);

        if (!empty($detailData)) {
            DetailOrder::insert($detailData);
        }

        [$taxAmount, $grandTotal] = $this->calculateTotals($subTotal);

        $order->update([
            'sub_total'   => $subTotal,
            'tax_amount'  => $taxAmount,
            'grand_total' => $grandTotal,
        ]);

        return $order->fresh();
    }

    /**
     * Create base order with user association.
     */
    private function createOrder(CreateOrderDto $dto, $user): Order
    {
        return Order::createWithUser([
            'status'      => OrderStatus::PENDING,
            'sub_total'   => 0,
            'tax_amount'  => 0,
            'grand_total' => 0,
            'note'        => $dto->note,
        ], $user);
    }

    /**
     * Retrieve all products referenced in DTO.
     */
    private function getProducts(CreateOrderDto $dto)
    {
        $productIds = collect($dto->items)->pluck('product_id')->unique();

        $data =  Product::with(['discounts'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
        return $data;
    }

    /**
     * Aggregate duplicate product items by product_id.
     */
    private function aggregateItems(CreateOrderDto $dto)
    {
        return collect($dto->items)
            ->groupBy('product_id')
            ->map(fn($items) => [
                'product_id' => $items->first()['product_id'],
                'quantity'   => $items->sum('quantity'),
            ])
            ->values();
    }

    /**
     * Build detail order data array and compute subtotal.
     */
    private function buildDetailOrders(Order $order, $aggregatedItems, $products): array
    {
        $detailData = [];
        $subTotal = 0;

        foreach ($aggregatedItems as $item) {
            $product = $products->get($item['product_id']);
            if (!$product) continue;

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

            $detailData[] = [
                'order_id'        => $order->id,
                'product_id'      => $product->id,
                'discount_id'     => $discountId,
                'quantity'        => $qty,
                'unit_price'      => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_price'     => $totalPrice,
                'product_data'    => json_encode($snapshot),
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            $subTotal += $totalPrice;
        }

        return [$detailData, $subTotal];
    }

    /**
     * Calculate tax and grand total.
     */
    private function calculateTotals(float $subTotal): array
    {
        $taxRate = config('order.tax_rate', 10);
        $taxAmount = round($subTotal * $taxRate / 100, 2);
        $grandTotal = $subTotal + $taxAmount;

        return [$taxAmount, $grandTotal];
    }

    /**
     * Snapshot product at order time.
     */
    private function makeProductSnapshot(Product $product): array
    {
        return [
            'id'              => $product->id,
            'code'            => $product->code,
            'name'            => $product->name,
            'price'           => (float) $product->price,
            'final_price'     => (float) $product->final_price,
            'active_discount' => $product->active_discount,
            'photo'           => $product->photo,
        ];
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
