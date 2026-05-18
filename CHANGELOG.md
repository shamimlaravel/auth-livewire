# Changelog

## [1.0.0] — 2026-05-18

### Fixed
- Critical: Removed duplicate IP change notification (UserObserver was firing alongside IpValidationService, sending two emails per suspicious login)
- Critical: Fixed seller role middleware — changed comma separator to pipe (`seller,reseller` → `seller|reseller`) to match Spatie's expected format
- Critical: API email verification now requires signed URL parameters (`{id}/{hash}`) instead of bypassing verification for any authenticated user
- Critical: Device fingerprint `updateOrCreate` no longer overwrites other users' records (added `user_id` scope to match conditions)
- API: Removed redundant `last_login_ip`/`last_login_at` double-write after authentication
- 2FA: "Remember me" preference is now preserved through the two-factor challenge flow
- OTP: Input state no longer reveals OTP field when form validation fails
- HIBP: Parsing now handles both Windows (`\r\n`) and Unix (`\n`) line endings
- Magic links: Prior valid tokens are now invalidated when requesting a new one
- 2FA: Added null guard for `two_factor_secret` before attempting verification

### Refactored
- Removed 4 unused Event classes (UserRegistered, UserLoggedIn, UserLoggedOut, SuspiciousLoginDetected)
- Removed 4 unused DTOs (TwoFactorDTO, MagicLinkDTO, SendOtpDTO, VerifyOtpLoginDTO)
- Removed 3 unused Exception classes (AuthenticationException, DeviceMismatchException, IpNotWhitelistedException)
- Removed 2 unused Security Actions (LogAuthenticationAttempt, ValidateDeviceFingerprint)
- Removed 4 thin Action wrappers (LoginUserAction, RegisterUserAction, SendOtpAction, SendMagicLinkAction) — callers now inject services directly
- Removed unused LoginStatus enum, DeviceFingerprintRule, AuthResource
- Removed unused web Form Requests (LoginRequest, RegisterRequest)
- Centralized duplicate 2FA enable logic into `TwoFactorService::setup()` — both API and web controllers now share the same setup code
- Removed unused `magic-link` rate limiter definition

### Cleanup
- Deleted empty `.cursor/` directory
- Deleted `tools/` directory (development debugging artifacts)
- Deleted `postman/` directory (stale export — use `laravel-api-to-postman` package instead)
- Deleted `translate.php` (one-off script that generated 78 fake language files)
- Deleted `docs/` HTML directory (duplicate of `docs.md`)
- Deleted `.kilo/node_modules/` and `.kilo/plans/` (committed dependencies — now gitignored)
- Added missing DB indexes on `profiles.user_id`, `addresses.profile_id`, `social_accounts.user_id`
- Fixed `AddressFactory` default `is_primary` from `true` to `false`

### Tests
- All 75 tests pass (143 assertions)
