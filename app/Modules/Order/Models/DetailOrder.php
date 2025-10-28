<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Casts\ProductDataCast;
use App\Modules\Product\Models\Discount;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model
{
    use SoftDeletes;

    protected $table = 'detail_orders';

    protected $fillable = [
        'quantity',
        'unit_price',
        'total_price',
        'discount_amount',
        'product_data',
        'order_id',
        'product_id',
        'discount_id',
    ];

    protected $casts = [
        'discount_amount' => 'float',
        'total_price' => 'float',
        'unit_price' => 'float',
        'quantity' => 'integer',
        'discount_id' => 'integer',
        'product_id' => 'integer',
        'order_id' => 'integer',
        'product_data' => ProductDataCast::class,
    ];

    /**
     * Get the order that owns the detail order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the detail order.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
