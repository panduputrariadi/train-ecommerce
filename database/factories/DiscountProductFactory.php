<?php

namespace Database\Factories;

use App\Modules\Product\Models\Discount;
use App\Modules\Product\Models\DiscountProduct;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DiscountProductFactory extends Factory
{
    protected $model = DiscountProduct::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discount_id' => Discount::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'created_by' => User::inRandomOrder()->value('id'),
        ];
    }
}
