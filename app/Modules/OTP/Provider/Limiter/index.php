<?php

use App\Modules\OTP\Provider\Limiter\SendOtpLimiter;
use App\Modules\OTP\Provider\Limiter\ForgetPasswordOtpLimiter;
use Illuminate\Support\Facades\RateLimiter;

return function () {
    RateLimiter::for(SendOtpLimiter::key(), function ($request) {
        return SendOtpLimiter::resolve($request);
    });

    RateLimiter::for(ForgetPasswordOtpLimiter::key(), function ($request) {
        return ForgetPasswordOtpLimiter::resolve($request);
    });
};
