<?php

namespace App\Base;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

abstract class BaseFormRequestLimiter
{
    abstract public static function key(): string;
    abstract public static function requestClass(): string;
    abstract public static function maxAttempts(): int;
    abstract public static function decayMinutes(): int;


    public static function resolve(Request $request): Limit
    {
        $rules = (new (static::requestClass()))->rules();

        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return Limit::none();
        }

        $identifier = $request->ip() . '|' . $request->input('email');

        return Limit::perMinutes(static::decayMinutes(), static::maxAttempts())->by($identifier);
    }

    public static function tooManyAttemptsResponse(Request $request)
    {
        $key = static::key() . '|' . $request->ip() . '|' . $request->input('email');
        $seconds = RateLimiter::availableIn($key);

        return response()->json([
            'message' => 'Too many attempts. Please try again in ' . $seconds . ' second.',
            'retry_after' => $seconds
        ]);
    }

}
