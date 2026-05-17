<?php

namespace App\Actions\Auth;

use App\Services\Auth\MagicLinkService;

readonly class SendMagicLinkAction
{
    public function __construct(private MagicLinkService $magicLinkService) {}

    public function execute(string $email, ?string $ip = null, ?string $ua = null): void
    {
        $this->magicLinkService->send($email, $ip, $ua);
    }
}
