<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PaymentMethodSeeder::class,
            BankAccountSeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            UserRoleSeeder::class,
            AddressSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            DiscountSeeder::class,
            DiscountProductSeeder::class
        ]);
    }
}
