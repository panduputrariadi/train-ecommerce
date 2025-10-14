<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Helper\CodeGenerator;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use SoftDeletes, HasGenerateCode, HasActivityUser;

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

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function getCodePrefix(): string
    {
        return 'PRD';
    }

    public function getCodeNamde(): string
    {
        return $this->name;
    }

    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    /**
     * Get the discounts associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Discount,$this>
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_products')
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            });
    }

    /**
     * Get the active discount of the product.
     *
     * @return array{
     *     type: string,
     *     code: string,
     *     value: int,
     *     expired_at: ?string
     * }|null The active discount of the product, or null if no active discount is found.
     */
    public function getActiveDiscountAttribute(): ?array
    {
        $discount = $this->relationLoaded('discounts')
            ? $this->discounts->first()
            : $this->discounts()->first();

        if (! $this->is_discount || ! $discount) {
            return null;
        }

        return [
            'id' => $discount->id,
            'type' => $discount->type,
            'code' => $discount->code,
            'value' => (int) $discount->value,
            'expired_at' => $discount->expired_at,
        ];
    }


    /**
     * Get the final price of the product.
     *
     * @return int The final price of the product.
     */
    public function getFinalPriceAttribute(): int
    {
        $price = (int) $this->price;

        $discount = $this->relationLoaded('discounts')
            ? $this->discounts->first()
            : $this->discounts()->first();

        if (! $this->is_discount || ! $discount) {
            return $price;
        }

        if ($discount->type === 'percentage') {
            return max(0, (int) ($price - ($price * $discount->value / 100)));
        }

        return max(0, (int) ($price - $discount->value));
    }

    /**
     * Create a new product with auto-generated code.
     */
    public static function createWithCode(array $attributes): self
    {
        $attributes['code'] = CodeGenerator::generate(
            'products',
            'PRD',
            $attributes['name']
        );

        $attributes['created_by'] = $attributes['created_by'] ?? Auth::id();

        return static::create($attributes);
    }
}
