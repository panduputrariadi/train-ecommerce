<?php

namespace App\Modules\Order\SnapShots;

use App\Base\BaseSnapshot;

class AddressSnapshot extends BaseSnapshot
{
    public string $address;
    public string $city;
    public string $state;
    public string $country;
    public string $zipCode;
}
