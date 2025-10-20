<?php

namespace App\Modules\Order\Casts;

use App\Modules\Order\ValueObject\AddressData;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AddressDataCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        $data = json_decode($value, true);

        return AddressData::fromArray($data);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof AddressData) {
            return json_encode($value->toArray());
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
