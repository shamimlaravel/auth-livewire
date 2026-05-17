<?php

namespace App\Enums;

enum AuthProvider: string
{
    case Email = 'email';
    case MagicLink = 'magic_link';
    case Google = 'google';
    case Facebook = 'facebook';
    case GitHub = 'github';
}
