<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Helper\CodeGenerator;
use App\Modules\Share\Models\Image;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasActivityUser, HasGenerateCode, SoftDeletes;

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
     * Get the images associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Image, $this>
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get the photo attribute.
     */
    public function getPhotoAttribute(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        return $image?->path;
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
     * Create a new product with the given attributes and generate a code for it.
     *
     * The code is generated using the CodeGenerator class, with the prefix 'PRD' and the product name.
     * The created_by attribute is automatically set to the current authenticated user's ID if it is not provided.
     *
     * @param  array  $attributes  The attributes for the new product.
     * @return self The created product.
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

    /**
     * Scope a query to search products by name or code.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $search = null)
    {
        return $query->when(
            $search,
            fn ($q) => $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%"))
        );
    }

    /**
     * Scope a query to filter products by category id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCategory($query, $categoryId)
    {
        return $query->when($categoryId, fn ($q) => $q->where('category_id', $categoryId));
    }

    /**
     * Scope a query to filter products by whether they have a discount or not.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isDiscounted  Whether the product has a discount or not.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDiscounted($query, $isDiscounted = null)
    {
        return $query->when(
            $isDiscounted !== null,
            fn ($q) => $q->where('is_discount', (bool) $isDiscounted)
        );
    }

    /**
     * Scope a query to filter products by price range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $min  The minimum price of the products.
     * @param  int|null  $max  The maximum price of the products.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriceBetween($query, $min = null, $max = null)
    {
        return $query
            ->when($min !== null, fn ($q) => $q->where('price', '>=', (int) $min))
            ->when($max !== null, fn ($q) => $q->where('price', '<=', (int) $max));
    }

    /**
     * Scope a query to filter products by whether they are in stock or not.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $inStock  Whether the product is in stock or not.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock($query, $inStock = null)
    {
        return $query->when(
            $inStock !== null,
            fn ($q) => $inStock ? $q->where('stock', '>', 0) : $q->where('stock', 0)
        );
    }

    /**
     * Scope a query to filter products by creation date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \DateTimeInterface|string|null  $from  The start date of the creation date range.
     * @param  \DateTimeInterface|string|null  $to  The end date of the creation date range.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBetween($query, $from = null, $to = null)
    {
        return $query
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to));
    }
}
