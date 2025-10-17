<?php

namespace Database\Factories;

use App\Modules\Share\Models\Role;
use App\Modules\Share\Models\User;
use App\Modules\Share\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'role_id' => Role::inRandomOrder()->value('id'),
        ];
    }
}
