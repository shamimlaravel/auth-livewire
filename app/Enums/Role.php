<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case User = 'user';
    case Seller = 'seller';
    case Reseller = 'reseller';
    case SupportAgent = 'support_agent';
}
