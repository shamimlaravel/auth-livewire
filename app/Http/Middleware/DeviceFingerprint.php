<?php

namespace App\Http\Middleware;

use App\Enums\AuditEvent;
use App\Services\Security\AuditService;
use App\Services\Security\DeviceFingerprintService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceFingerprint
{
    public function __construct(
        private readonly DeviceFingerprintService $deviceFingerprintService,
        private readonly AuditService $auditService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && $request->header('X-Device-Fingerprint')) {
            $fingerprint = $request->header('X-Device-Fingerprint');

            $known = $this->deviceFingerprintService->isKnown(
                $request->user(),
                $fingerprint,
            );

            if (! $known) {
                $this->deviceFingerprintService->record(
                    $request->user(),
                    $fingerprint,
                    $request->header('X-Platform'),
                    $request->header('User-Agent'),
                );

                $this->auditService->log(
                    event: AuditEvent::DeviceFingerprintMismatch,
                    user: $request->user(),
                    ipAddress: $request->ip(),
                    userAgent: $request->userAgent(),
                );
            }
        }

        return $response;
    }
}
