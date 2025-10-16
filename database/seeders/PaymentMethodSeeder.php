<?php

namespace Database\Seeders;

use App\Modules\Payment\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash', 'type' => 'cash'],
            ['name' => 'Bank Transfer', 'type' => 'transfer'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate($method);
        }
    }
}
