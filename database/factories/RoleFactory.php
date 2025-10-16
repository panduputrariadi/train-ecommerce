<?php

namespace Database\Factories;

use App\Modules\Share\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $roles = [
            'admin.super',
            'admin.moderator',
            'vendor.premium',
            'vendor.basic',
            'customer.regular',
            'customer.premium',
            'customer.guest',
            'support.agent',
            'marketing.manager',
        ];

        $slug = $this->faker->unique()->randomElement($roles);
        $name = ucwords(str_replace('.', ' ', $slug));

        return [
            'slug' => $slug,
            'name' => $name,
        ];
    }
}
