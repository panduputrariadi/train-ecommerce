<?php

namespace App\Modules\OTP\Provider\Limiter;

use App\Base\BaseFormRequestLimiter;
use App\Modules\OTP\Requests\SendOtpRequest;

class SendOtpLimiter extends BaseFormRequestLimiter
{
    public static function key(): string
    {
        return 'send-otp';
    }

    public static function requestClass(): string
    {
        return SendOtpRequest::class;
    }

    public static function maxAttempts(): int
    {
        return 2;
    }

    public static function decayMinutes(): int
    {
        return 1;
    }
}
