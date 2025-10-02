<?php

namespace Database\Seeders;

use App\Modules\Share\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        foreach ($roles as $slug) {
            Role::firstOrCreate(
                ['slug' => $slug],
                ['name' => ucwords(str_replace('.', ' ', $slug))]
            );
        }
    }
}
