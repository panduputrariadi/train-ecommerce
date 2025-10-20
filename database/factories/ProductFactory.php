<?php

namespace Database\Factories;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Share\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->numerify('##########'),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'is_discount' => $this->faker->boolean,
            'stock' => $this->faker->randomDigitNotNull,
            'created_by' => User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
        ];
    }
}
