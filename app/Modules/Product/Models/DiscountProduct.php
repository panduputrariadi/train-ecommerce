<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountProduct extends Model
{
    protected $table = 'discount_products';

    protected $fillable = [
        'discount_id',
        'product_id',
        'created_by',
    ];

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
