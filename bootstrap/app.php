<?php

use App\Exceptions\AuthenticationException;
use App\Exceptions\DeviceMismatchException;
use App\Exceptions\IpNotWhitelistedException;
use App\Http\Middleware\DeviceFingerprint;
use App\Http\Middleware\IpWhitelist;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrackLoginSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ip-whitelist' => IpWhitelist::class,
            'device-fingerprint' => DeviceFingerprint::class,
            'track-login' => TrackLoginSession::class,
            'role' => RoleMiddleware::class,
        ]);

        $middleware->api(prepend: [
            'throttle:api',
        ]);

        $middleware->web(append: [
            TrackLoginSession::class,
            DeviceFingerprint::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (IpNotWhitelistedException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (DeviceMismatchException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        });
    })->create();
