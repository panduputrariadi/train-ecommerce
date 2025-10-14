<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Casts\ProductDataCast;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Traits\HasActivityUser;
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
        'product_data' => ProductDataCast::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
