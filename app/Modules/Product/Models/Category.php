<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    /**
     * Get the products associated with the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Product,$this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
