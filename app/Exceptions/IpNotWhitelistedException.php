<?php

namespace App\Exceptions;

use Exception;

class IpNotWhitelistedException extends Exception
{
    public function __construct(string $message = 'IP address is not whitelisted', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
