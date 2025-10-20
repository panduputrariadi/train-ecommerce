<?php

namespace Database\Seeders;

use App\Modules\Product\Models\DiscountProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscountProduct::factory(10)->create();
    }
}
