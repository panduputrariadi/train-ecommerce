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
     * Execute order creation or append items to existing pending order.
     *
     * @param  CreateOrderDto  $dto
     * @return Order
     */
    public function execute(CreateOrderDto $dto): Order
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $order) {
            $order = Order::create([
                'code'         => CodeGenerator::generate('orders', 'ORD', $user->profile->name),
                'status'       => OrderStatus::PENDING,
                'sub_total'    => 0,
                'tax_amount'   => 0,
                'grand_total'  => 0,
                'note'         => $dto->note,
                'user_id'      => $user->id,
            ]);
        }

        foreach ($dto->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            $unitPrice      = (float) $product->price;
            $qty            = (int) $item['quantity'];
            $discountAmount = (float) ($item['discount_amount'] ?? 0);
            $activeDiscount = $product->active_discount;
            $finalUnitPrice = (float) $product->final_price;

            if ($discountAmount <= 0 && $activeDiscount) {
                $discountAmount = ($unitPrice - $finalUnitPrice) * $qty;
            }

            $totalPrice = ($unitPrice * $qty) - $discountAmount;

            $existingDetail = DetailOrder::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingDetail) {
                $newQty = $existingDetail->quantity + $qty;
                $newTotalPrice = ($unitPrice * $newQty) - ($existingDetail->discount_amount + $discountAmount);

                $existingDetail->update([
                    'quantity'        => $newQty,
                    'total_price'     => $newTotalPrice,
                    'discount_amount' => $existingDetail->discount_amount + $discountAmount,
                    'updated_at'      => now(),
                ]);
            } else {
                DetailOrder::create([
                    'order_id'        => $order->id,
                    'product_id'      => $product->id,
                    'discount_id'     => $activeDiscount['id'] ?? null,
                    'quantity'        => $qty,
                    'unit_price'      => $unitPrice,
                    'total_price'     => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'product_data'    => json_encode([
                        'code'            => $product->code,
                        'name'            => $product->name,
                        'final_price'     => $product->final_price,
                        'active_discount' => $product->active_discount,
                        'photo'           => $product->photo,
                    ]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }

        $this->recalculateOrderTotals($order);

        return $order->fresh(['details']);
    }

    /**
     * Recalculate subtotal, tax, and grand total for an order
     */
    private function recalculateOrderTotals(Order $order): void
    {
        $details = $order->details()->get();

        $subTotal = $details->sum(fn ($d) => (float) $d->total_price);

        $taxRate = config('order.tax_rate', 10); // default 10%
        $taxAmount = round($subTotal * $taxRate / 100, 2);
        $grandTotal = $subTotal + $taxAmount;

        $order->update([
            'sub_total'   => $subTotal,
            'tax_amount'  => $taxAmount,
            'grand_total' => $grandTotal,
        ]);
    }
}
