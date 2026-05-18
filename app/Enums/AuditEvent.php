<?php

namespace App\Enums;

enum AuditEvent: string
{
    case Registered = 'registered';
    case LoggedIn = 'logged_in';
    case LoggedOut = 'logged_out';
    case LoginFailed = 'login_failed';
    case PasswordReset = 'password_reset';
    case PasswordResetRequested = 'password_reset_requested';
    case EmailVerified = 'email_verified';
    case TwoFactorEnabled = 'two_factor_enabled';
    case TwoFactorDisabled = 'two_factor_disabled';
    case TwoFactorChallenged = 'two_factor_challenged';
    case MagicLinkRequested = 'magic_link_requested';
    case MagicLinkUsed = 'magic_link_used';
    case SocialLoginLinked = 'social_login_linked';
    case SocialLoginUnlinked = 'social_login_unlinked';
    case IpChanged = 'ip_changed';
    case DeviceFingerprintMismatch = 'device_fingerprint_mismatch';
    case SuspiciousLogin = 'suspicious_login';
    case ProfileUpdated = 'profile_updated';
    case RoleAssigned = 'role_assigned';
    case RoleRemoved = 'role_removed';
    case AccountDeactivated = 'account_deactivated';
    case AccountReactivated = 'account_reactivated';
    case OtpLoginRequested = 'otp_login_requested';
    case OtpLoginVerified = 'otp_login_verified';
    case OtpLoginFailed = 'otp_login_failed';
}
