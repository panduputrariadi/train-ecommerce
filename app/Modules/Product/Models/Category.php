<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HasGenerateCode, HasActivityUser;

    protected $table = 'categories';

    protected $fillable = [
        'slug',
        'code',
        'name',
        'description',
    ];

    /**
     * Get the prefix code for the category.
     *
     * @return string
     */
    protected function getCodePrefix(): string
    {
        return 'CAT';
    }

    /**
     * Get the name of the category.
     *
     * This method returns the name of the category as a string.
     * If the category does not have a name, it returns null.
     *
     * @return string|null
     */
    public function getCodeName(): ?string
    {
        return $this->name;
    }

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
