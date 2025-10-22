<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Models\User;
use Database\Factories\DiscountProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountProduct extends Model
{
    use HasFactory;

    protected $table = 'discount_products';

    protected $fillable = [
        'discount_id',
        'product_id',
        'created_by',
    ];

    protected static function newFactory(): DiscountProductFactory
    {
        return DiscountProductFactory::new();
    }

    /**
     * Get the discount that owns the discount product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Discount,$this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Get the product that owns the discount product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that created the discount product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
