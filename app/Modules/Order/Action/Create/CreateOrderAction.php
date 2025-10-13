<?php

namespace App\Modules\Order\Action\Create;

use App\Modules\Order\DTOs\CreateOrderDto;
use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\DetailOrder;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Helper\CodeGenerator;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    /**
     * Execute the creation of a new order with the given items
     *
     * @param CreateOrderDto $dto
     * @return Order
     *
     */
    public function execute(CreateOrderDto $dto): Order
    {
        $user = Auth::user();

        $order = $this->findOrCreatePendingOrder($user->id, $dto->note);

        foreach ($dto->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $snapshot = $this->makeProductSnapshot($product);
            $qty = (int) $item['quantity'];

            $discountAmount = $this->calculateDiscountAmount(
                $snapshot['price'],
                $snapshot['active_discount'],
                $qty
            );

            $existingDetail = $this->findExistingDetail($order->id, $product->id);

            $existingDetail
                ? $this->mergeExistingDetail($existingDetail, $qty)
                : $this->createNewDetail($order, $product, $snapshot, $qty, $discountAmount);
        }

        $this->recalculateOrderTotals($order);

        return $order->fresh(['details']);
    }

    /**
     * Find or create a pending order with the given user ID and note
     *
     * @param int $userId
     * @param string $note [optional]
     * @return Order
     */
    private function findOrCreatePendingOrder(int $userId, ?string $note = null): Order
    {
        return Order::firstOrCreate(
            ['user_id' => $userId, 'status' => OrderStatus::PENDING],
            [
                // 'code'         => CodeGenerator::generate('orders', 'ORD', Auth::user()->profile->name),
                'status'       => OrderStatus::PENDING,
                'sub_total'    => 0,
                'tax_amount'   => 0,
                'grand_total'  => 0,
                'note'         => $note,
            ]
        );
    }

    /**
     * Create a snapshot of the product
     *
     * This function creates an array containing the product's code, name, price, final price, active discount, and photo
     *
     * @param Product $product
     * @return array
     */
    private function makeProductSnapshot(Product $product): array
    {
        return [
            'code'            => $product->code,
            'name'            => $product->name,
            'price'           => $product->price,
            'final_price'     => $product->final_price,
            'active_discount' => $product->active_discount,
            'photo'           => $product->photo,
        ];
    }

    /**
     * Calculate the discount amount given a price, discount, and quantity
     *
     * If the discount is null, this function returns 0.
     *
     * If the discount type is 'percentage', this function returns the price multiplied by (discount value / 100) multiplied by the quantity.
     * If the discount type is 'nominal', this function returns the discount value multiplied by the quantity.
     * If the discount type is neither 'percentage' nor 'nominal', this function returns 0.
     *
     * @param float $price
     * @param array|null $discount
     * @param int $qty
     * @return float
     */
    private function calculateDiscountAmount(float $price, ?array $discount, int $qty): float
    {
        if (! $discount) {
            return 0;
        }

        $value = (float) ($discount['value'] ?? 0);
        return match ($discount['type']) {
            'percentage' => $price * ($value / 100) * $qty,
            'nominal'    => $value * $qty,
            default      => 0,
        };
    }

    /**
     * Find an existing detail order by order ID and product ID
     *
     * @param int $orderId
     * @param int $productId
     * @return DetailOrder|null
     */
    private function findExistingDetail(int $orderId, int $productId): ?DetailOrder
    {
        return DetailOrder::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Merge an existing detail order with a new quantity
     *
     * @param DetailOrder $detail The existing detail order to merge
     * @param int $newQty The new quantity to add to the existing detail order
     *
     * This function will update the existing detail order with the new quantity
     * and calculate the new total price and discount amount accordingly.
     */
    private function mergeExistingDetail(DetailOrder $detail, int $newQty): void
    {
        $snapshot = json_decode($detail->product_data, true) ?? [];
        $basePrice = (float) ($snapshot['price'] ?? $detail->unit_price);
        $discount = $snapshot['active_discount'] ?? null;

        $qty = $detail->quantity + $newQty;
        $discountAmount = $this->calculateDiscountAmount($basePrice, $discount, $qty);
        $total = ($basePrice * $qty) - $discountAmount;

        $detail->update([
            'quantity'        => $qty,
            'discount_amount' => $discountAmount,
            'total_price'     => $total,
        ]);
    }

    /**
     * Create a new detail order
     *
     * @param Order $order The order that the detail order belongs to
     * @param Product $product The product that the detail order is for
     * @param array $snapshot The snapshot of the product data when the order was created
     * @param int $qty The quantity of the product in the detail order
     * @param float $discountAmount The total discount amount for the detail order
     */
    private function createNewDetail(Order $order, Product $product, array $snapshot, int $qty, float $discountAmount): void
    {
        $unitPrice = (float) $snapshot['price'];
        $totalPrice = ($unitPrice * $qty) - $discountAmount;

        DetailOrder::create([
            'order_id'        => $order->id,
            'product_id'      => $product->id,
            'discount_id'     => $snapshot['active_discount']['id'] ?? null,
            'quantity'        => $qty,
            'unit_price'      => $unitPrice,
            'total_price'     => $totalPrice,
            'discount_amount' => $discountAmount,
            'product_data'    => json_encode($snapshot),
        ]);
    }

    /**
     * Recalculate the order's totals
     *
     * This function recalculates the order's subtotal, tax amount, and grand total based on the total price of all its detail orders.
     *
     * @param Order $order The order to recalculate the totals for
     */
    private function recalculateOrderTotals(Order $order): void
    {
        $details = $order->details()->get();
        $subTotal = $details->sum(fn ($d) => $d->total_price);
        $taxRate = config('order.tax_rate', 10);
        $taxAmount = round($subTotal * $taxRate / 100, 2);

        $order->update([
            'sub_total'   => $subTotal,
            'tax_amount'  => $taxAmount,
            'grand_total' => $subTotal + $taxAmount,
        ]);
    }
}
