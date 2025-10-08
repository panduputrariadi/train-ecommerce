<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    protected $fillable = ['type', 'code', 'value', 'expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Get the products associated with the discount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Product,$this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_products');
    }
}
