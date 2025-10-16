<?php

namespace App\Modules\Order\Rule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ExistsAllProducts implements ValidationRule
{
    /**
     * Validate if all products in the given array exist or are not invalid.
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! is_array($value) || ! isset($value[0]['product_id'])) {
            return;
        }

        $ids = collect($value)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $count = DB::table('products')
            ->whereIn('id', $ids)
            ->count();

        if ($count !== $ids->count()) {
            $fail('Some products do not exist or are invalid.');
        }
    }
}
