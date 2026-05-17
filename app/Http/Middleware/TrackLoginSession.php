<?php

namespace App\Http\Middleware;

use App\Services\Security\IpValidationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLoginSession
{
    public function __construct(private readonly IpValidationService $ipValidationService) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $this->ipValidationService->validateIpChange(
                $request->user(),
                $request->ip(),
            );
        }

        return $next($request);
    }
}
