<?php

namespace App\Modules\Share\Enum;

enum RoleIdUser: int
{
    case ADMIN_SUPER = 1;
    case REGULAR_CUSTOMER = 5;

    public function label(): int
    {
        return match($this) {
            self::ADMIN_SUPER => 1,
            self::REGULAR_CUSTOMER => 5
        };
    }
}
