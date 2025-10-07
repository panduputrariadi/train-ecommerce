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
            ->withPivot(['created_by', 'created_at'])
            ->withTimestamps();
    }
}
