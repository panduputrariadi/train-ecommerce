<?php

namespace Database\Factories;

use App\Modules\Product\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'code' => $this->faker->numerify('##########'),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
        ];
    }
}
