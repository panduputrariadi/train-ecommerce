<?php

namespace App\Modules\OTP\Provider\Limiter;

use App\Base\BaseFormRequestLimiter;
use App\Modules\OTP\Requests\ForgetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ForgetPasswordOtpLimiter extends BaseFormRequestLimiter
{
    public static function key(): string
    {
        return 'forget-password-otp';
    }

    public static function requestClass(): string
    {
        return ForgetPasswordRequest::class;
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
