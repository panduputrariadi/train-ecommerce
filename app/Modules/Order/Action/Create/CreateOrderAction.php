<?php

namespace App\Modules\Order\Action\Create;

use App\Modules\Order\DTOs\CreateOrderDto;
use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\DetailOrder;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Services\PriceCalculateService;
use App\Modules\Order\SnapShots\AddressSnapshot;
use App\Modules\Order\SnapShots\ProductSnapShot;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Models\Address;
use App\Modules\Share\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    private PriceCalculateService $priceCalculator;

    public function __construct(PriceCalculateService $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * Execute the creation of a new order.
     *
     * @param  CreateOrderDto  $dto  The validated CreateOrderDto.
     * @return Order The created order.
     */
    public function execute(CreateOrderDto $dto): Order
    {
        $user = Auth::user()->loadMissing(['profile', 'addresses']);
        $order = $this->createOrder($dto, $user);

        $products = $this->getProducts($dto);
        $aggregatedItems = $this->aggregateItems($dto);

        [$detailData, $subTotal] = $this->buildDetailOrders($order, $aggregatedItems, $products);

        if (! empty($detailData)) {
            DetailOrder::insert($detailData);
        }

        $totals = $this->priceCalculator->calculateTotals($subTotal);
        $taxAmount = $totals['tax_amount'];
        $grandTotal = $totals['grand_total'];

        $order->update([
            'sub_total' => $subTotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
        ]);

        return $order;
    }

    /**
     * Create a new order with the given attributes and associate it with the given user.
     *
     * This method generates a code for the order using the given user's name (or 'UNKNOWN' if the user is not logged in).
     *
     * @param  CreateOrderDto  $dto  The validated CreateOrderDto.
     * @param  \App\Modules\Share\Models\User  $user  The user to associate with the order.
     * @return Order The created order.
     */
    private function createOrder(CreateOrderDto $dto, $user): Order
    {
        $selectedAddress = $user->addresses->where('user_id', $dto->addressId)->first();
        if (! $selectedAddress) {
            $selectedAddress = $user->addresses->first();
        }
        $snapshot = $this->makeAddressSnapshot($selectedAddress);

        return Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING,
            'sub_total' => 0,
            'tax_amount' => 0,
            'grand_total' => 0,
            'note' => $dto->note,
            'user_data' => json_encode($snapshot),
        ], $user);
    }

    /**
     * Retrieve products from database based on product_ids in CreateOrderDto.
     * Products are retrieved with their discounts.
     *
     * @param  CreateOrderDto  $dto  The validated CreateOrderDto.
     * @return \Illuminate\Support\Collection A collection of products keyed by product_id.
     */
    private function getProducts(CreateOrderDto $dto)
    {
        $productIds = collect($dto->items)->pluck('product_id')->unique();

        $data = Product::with(['discounts', 'images'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return $data;
    }

    /**
     * Aggregate items by product_id.
     *
     * @param  CreateOrderDto  $dto  The validated CreateOrderDto.
     * @return \Illuminate\Support\Collection A collection of aggregated items.
     */
    private function aggregateItems(CreateOrderDto $dto)
    {
        return collect($dto->items)
            ->groupBy('product_id')
            ->map(fn ($items) => [
                'product_id' => $items->first()['product_id'],
                'quantity' => $items->sum('quantity'),
            ])
            ->values();
    }

    /**
     * Build detail orders based on aggregated items and products.
     *
     * @param  Order  $order  The order to attach the detail orders to.
     * @param  \Illuminate\Support\Collection  $aggregatedItems  A collection of aggregated items keyed by product_id.
     * @param  \Illuminate\Support\Collection  $products  A collection of products keyed by product_id.
     * @return array An array containing the detail orders and the sub total of the detail orders.
     */
    private function buildDetailOrders(Order $order, $aggregatedItems, $products): array
    {
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
            $discountAmount = $this->priceCalculator->calculateDiscountAmount(
                $snapshot['price'],
                $snapshot['active_discount'],
                $qty
            );

            $detailData[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'discount_id' => $discountId,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'product_data' => json_encode($snapshot),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $subTotal += $totalPrice;
        }

        return [$detailData, $subTotal];
    }

    /**
     * Make a snapshot of a product.
     *
     * @param  Product  $product  The product to make a snapshot of.
     * @return array An array containing the snapshot of the product.
     */
    private function makeProductSnapshot(Product $product): array
    {
        return ProductSnapShot::fromModel($product)->toArray();
    }

    /**
     * Make a snapshot of a user.
     *
     * This method creates an array containing the id, name, email, phone, and address of the given user.
     *
     * @param  User  $user  The user to make a snapshot of.
     * @return array An array containing the snapshot of the user.
     */
    private function makeAddressSnapshot(Address $address): array
    {
        return AddressSnapshot::fromModel($address)->toArray();
    }
}
