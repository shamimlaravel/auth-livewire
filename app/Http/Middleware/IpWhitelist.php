<?php

namespace App\Http\Middleware;

use App\Services\Security\IpValidationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpWhitelist
{
    public function __construct(private readonly IpValidationService $ipValidationService) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->ipValidationService->isWhitelisted($request->ip())) {
            abort(403, __('auth.ip_not_whitelisted'));
        }

        return $next($request);
    }
}
