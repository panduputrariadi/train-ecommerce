<?php

namespace Database\Factories;

use App\Modules\Share\Models\User;
use App\Modules\Share\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'code' => $this->faker->numerify('##########'),
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
