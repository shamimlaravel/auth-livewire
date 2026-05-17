<?php

namespace App\Exceptions;

use Exception;

class DeviceMismatchException extends Exception
{
    public function __construct(string $message = 'Device fingerprint mismatch', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
