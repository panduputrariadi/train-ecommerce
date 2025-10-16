<?php

namespace App\Modules\Product\Models;

use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasActivityUser, HasGenerateCode;

    protected $fillable = ['type', 'code', 'value', 'expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected function getCodePrefix(): string
    {
        return 'DSC';
    }

    public function getCodeName(): string
    {
        return $this->type;
    }

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
