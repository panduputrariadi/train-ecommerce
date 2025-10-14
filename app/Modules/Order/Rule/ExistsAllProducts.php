<?php

namespace App\Modules\Order\Rule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ExistsAllProducts implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Jika $value bukan array of array, abaikan (mencegah error Undefined key)
        if (!is_array($value) || !isset($value[0]['product_id'])) {
            return;
        }

        // Ambil semua product_id unik dari items
        $ids = collect($value)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        // Hitung jumlah produk valid di DB
        $count = DB::table('products')
            ->whereIn('id', $ids)
            ->count();

        // Jika jumlah tidak cocok, berarti ada product_id yang tidak valid
        if ($count !== $ids->count()) {
            $fail('Some products do not exist or are invalid.');
        }
    }
}
