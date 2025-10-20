<?php

namespace App\Modules\Order\ValueObject;

use App\Base\BaseValueObject;

class AddressData extends BaseValueObject
{
    public string $address;

    public string $city;

    public string $state;

    public string $country;

    public string $zip_code;
}
