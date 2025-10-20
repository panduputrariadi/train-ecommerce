<?php

namespace Database\Factories;

use App\Modules\Product\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DiscountFactory extends Factory
{
    protected $model = Discount::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = ['percentage', 'nominal'];
        return [
            'code' => $this->faker->numerify('##########'),
            'type' => $type[array_rand($type)],
            'value' => ($type[array_rand($type)] == 'percentage') ? $this->faker->randomFloat(2, 0, 100) : $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
