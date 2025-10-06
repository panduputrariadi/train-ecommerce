<?php

use App\Modules\OTP\Provider\Command\CommandServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            // 'auth.sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // ⚠ kalau pakai SPA/cookie
        ]);

        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            $limiter = $request->route()?->getAction('throttle') ?? null;

            if ($limiter) {
                foreach (get_declared_classes() as $class) {
                    if (is_subclass_of($class, \App\Base\BaseFormRequestLimiter::class)) {
                        if ($class::key() === $limiter) {
                            return $class::tooManyAttemptsResponse($request);
                        }
                    }
                }
            }

            $headers = $e->getHeaders();
            $retryAfter = $headers['Retry-After'] ?? 0;

            return response()->json([
                'message' => 'Too many attempts. Please try again in '.$retryAfter.' second.',
                'retry_after' => $retryAfter,
            ], 429, $headers);
        });
    })->withProviders([
        CommandServiceProvider::class,
    ])
    ->create();
