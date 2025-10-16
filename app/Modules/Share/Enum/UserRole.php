<?php

namespace App\Modules\Share\Enum;

enum UserRole: string
{
    case ADMIN_SUPER = 'admin.super';
    case ADMIN_MODERATOR = 'admin.moderator';

    case VENDOR_PREMIUM = 'vendor.premium';
    case VENDOR_BASIC = 'vendor.basic';

    case CUSTOMER_REGULAR = 'customer.regular';
    case CUSTOMER_PREMIUM = 'customer.premium';
    case CUSTOMER_GUEST = 'customer.guest';

    case SUPPORT_AGENT = 'support.agent';
    case MARKETING_MANAGER = 'marketing.manager';

    public static function adminRoles(): array
    {
        return [
            self::ADMIN_SUPER->value,
            self::ADMIN_MODERATOR->value,
        ];
    }

    public static function isCustomer(): array
    {
        return [
            self::CUSTOMER_REGULAR->value,
            self::CUSTOMER_PREMIUM->value,
            self::CUSTOMER_GUEST->value,
        ];
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
