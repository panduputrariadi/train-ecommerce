<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'stock',
        'photo',
        'is_discount',
        'category_id',
        'created_by',
    ];

    protected $appends = ['final_price', 'active_discount'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_products')
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            });
    }

    public function getActiveDiscountAttribute(): ?array
    {
        $discount = $this->discounts->first();
        if (! $this->is_discount || ! $discount) {
            return null;
        }

        return [
            'type' => $discount->type,
            'code' => $discount->code,
            'value' => (int) $discount->value,
            'expired_at' => $discount->expired_at,
        ];
    }

    public function getFinalPriceAttribute(): int
    {
        $price = (int) $this->price;
        $discount = $this->discounts->first();

        if (! $this->is_discount || ! $discount) {
            return $price;
        }

        $calculateDisc = $discount;

        if ($discount->type === 'percentage') {
            return max(0, (int) ($price - ($price * $calculateDisc->value / 100)));
        }

        if ($discount->type === 'nominal') {
            return max(0, (int) ($price - $calculateDisc->value));
        }

        return $price;
    }
}
