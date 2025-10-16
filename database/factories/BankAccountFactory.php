<?php

namespace Database\Factories;

use App\Modules\Payment\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BankAccount::class;
    public function definition(): array
    {
        return [
            'bank_name'       => $this->faker->company,
            'account_number'  => $this->faker->numerify('##########'), // 10 digit
            'account_name'    => $this->faker->name,
            'payment_method_id' => 2,
            'is_active'       => $this->faker->boolean,
        ];
    }
}
