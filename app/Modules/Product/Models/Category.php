<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Category extends Model
{
    use HasActivityUser, HasFactory, HasGenerateCode, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'slug',
        'code',
        'name',
        'description',
    ];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    /**
     * Get the prefix code for the category.
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
