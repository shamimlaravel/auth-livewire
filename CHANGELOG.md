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

## [1.1.0] — 2026-05-20

### Added

#### Multi-Channel Authentication
- **AuthChannel enum** (`App\Enums\AuthChannel`) — four delivery channels: `sms`, `whatsapp`, `telegram`, `email`, each with `label()` and `icon()` helpers and `loginChannels()` / `twoFactorChannels()` lists.
- **MultiChannelOtpService** (`app/Services/Auth/MultiChannelOtpService`) — unified `send()` / `verify()` that accepts an `AuthChannel` argument; generates 6-digit OTPs with SHA-256 hashing, persists to `OtpToken`, invalidates prior unused tokens, and auto-expires after configurable TTL.
- **Drivers** per channel:
  - **SMS** — Barta SMS driver, enabled via `AUTH_CHANNELS_SMS_*` settings in `config/auth_channels.php`.
  - **WhatsApp** — Devsfort/Whatsapp driver, enabled via `AUTH_CHANNELS_WHATSAPP_*` settings in `config/auth_channels.php`.
  - **Telegram** — DefStudio/Telegraph driver, enabled via `AUTH_CHANNELS_TELEGRAM_*` settings in `config/auth_channels.php`.
  - **Email** — Laravel mail notification driver; enabled when `auth.otp.channel` is `email`.
- **`config/auth_channels.php`** — new config file defining per-channel credentials and driver class references.
- **OtpToken model** — new `channel` and `identifiable` columns; `isValid()` and `scopeValid()` helpers consider both `used_at` and `expires_at`.

#### Login Livewire Components per Channel
- **PhoneLogin** (`Login → Phone / SMS OTP`) — `phone` input, OTP code input, `sendOtp()` / `verifyOtp()` / `resendOtp()` actions.
- **WhatsAppLogin** (`Login → WhatsApp OTP`) — `whatsapp_number` input, same OTP flows as PhoneLogin.
- **TelegramLogin** (`Login → Telegram OTP`) — `telegram_chat_id` input, same OTP flows as PhoneLogin.
- **TelegramLogin** (`Login → Telegram OTP`) — `telegram_chat_id` input, same OTP flows as PhoneLogin.
- **Web routes**: `/login/phone`, `/login/whatsapp`, `/login/telegram` — all guest-only, accessible by `Route::get()`.

#### Multi-Channel 2FA Delivery
- **User::getTwoFactorTarget()** — resolves the 2FA delivery identifier based on `two_factor_channel` (`sms`→`phone`, `whatsapp`→`whatsapp_number`, `telegram`→`telegram_chat_id`, default→`email`).
- **User::getAvailableChannels()** — returns the full list of stored contact channels for each user.

#### Database Behind Auth Settings
- **AuthSetting model** (`app/Models/AuthSetting.php`) — encrypted key/value store with `get()`, `set()`, `isEnabled()`, and `allSettings()` static helpers.
- **AuthSettingsSeeder** — seeds factory-default auth settings on first install.
- **Four database migrations**:
  - `add_multichannel_auth_fields_to_users_table` — adds `phone`, `whatsapp_number`, `telegram_chat_id`, and `two_factor_channel`.
  - `add_multichannel_fields_to_otp_tokens_table` — adds `identifiable`, `channel`, and makes `email` nullable.
  - `create_auth_settings_table` — creates `auth_settings` key/value table with encrypted `value` column.
  - `make_otp_tokens_email_nullable` — nullability fix for channel-agnostic tokens.

#### Admin Auth Config Panel
- **AdminAuthConfig Livewire** (`app/Livewire/Admin/AdminAuthConfig.php`) — full admin panel at `/admin/auth-config` managing:
  - Channel enable/disable toggles (SMS, WhatsApp, Telegram, email).
  - OTP / magic-link expiry, rate limiting, throttle settings.
  - Per-channel credential fields (SMS gateway token, WhatsApp token, Telegram bot token).
  - **7 OAuth social login providers** — Google, GitHub, Facebook, **Twitter**, **LinkedIn**, **GitLab**, **Microsoft** — each with enabled/disabled toggle and client ID/secret fields.
- **Auth settings Blade view** (`resources/views/livewire/admin/admin-auth-config.blade.php`) — multi-section layout using the new `Accordion`, `Tabs`, `Toggle`, and `SettingsRow` UI components.
- **Web route**: `GET /admin/auth-config` — role:admin middleware protected.

#### UI Components
- **Accordion** (`app/View/Components/Ui/Accordion.php`) — collapsible section widget.
- **SettingsRow** (`app/View/Components/Ui/SettingsRow.php`) — labeled input row with inline description.
- **Tabs** (`app/View/Components/Ui/Tabs.php`) — horizontal tab bar + content switcher.
- **Toggle** (`app/View/Components/Ui/Toggle.php`) — on/off switch with label and optional description.

#### ProviderConfigService
- **ProviderConfigService** (`app/Services/Auth/ProviderConfigService.php`) — merges `config/services.php` with DB-saved `AuthSetting` values; `getAvailableProviders()` returns only enabled+configured providers, `getConfig()` returns the merged per-provider config.
- **Admin dashboard** integration — `AdminDashboard` counts `enabledCount`, `socialEnabledCount`, and passes to the Blade view.
- **7 providers** now tracked: google, github, facebook, twitter, linkedin, gitlab, microsoft.

#### Tests
- **SocialLoginTest** — OAuth redirect/callback flow, provider enable-disable, re-auth after account link.
- **MultiChannelLoginTest** — phone/SMS login happy path.
- **MultiChannelOtpTest** — send + verify cycle for SMS, WhatsApp, Telegram, and Email.
- **MultiChannelTwoFactorTest** — 2FA challenge delivery via selected channel.
- **UserMultiChannelTest** — `getTwoFactorTarget()` / `getAvailableChannels()` correctness and immutable channel validation.
- **AdminAuthConfigTest** — saves, loads, resets, and toggles social providers.
- **OtpLoginTest** — updated for refactored `OtpService` delegation.

### Changed / Refactored

- **OtpService** — converted from a self-contained implementation to a backward-compatible thin wrapper that delegates `send()` / `verify()` to `MultiChannelOtpService::send()` / `MultiChannelOtpService::verify()` with `AuthChannel::Email` channel; audit callbacks preserved.
- **AppServiceProvider boot** — reorganized: `configureRateLimiting()` extracted to a private method; `'auth'` rate-limiter set to 5/min, `'api'` to 60/min.
- **User model** — added `phone`, `whatsapp_number`, `telegram_chat_id` to `$fillable`; added `two_factor_channel` to `$casts`; added `getTwoFactorTarget()` and `getAvailableChannels()`.
- **OtpToken model** — added `channel` and `identifiable` to `$fillable`; `isValid()` and `scopeValid()` retained.
- **SocialLoginController** — updated social redirect/callback to accept dynamic, DB-saved provider credentials via `ProviderConfigService`.
- **TwoFactorChallenge Livewire** — updated to read 2FA delivery channel preferences from the updated `User` model.
- **Login Livewire** — updated login screen to expose Phone, WhatsApp, and Telegram OTP buttons alongside the existing password and magic-link options.
- **UserManager Livewire** — updated to show and mutate `phone`, `whatsapp_number`, and `telegram_chat_id`.
- **RoleManager Livewire** — minor updates to role toggle list presentation.
- **Admin Dashboard** — tracks `socialEnabledCount` and `enabledCount` for ` AdminAuthConfig` integration.
- **Admin App view** — adds link for the **Auth Config** nav item pointing to `/admin/auth-config`.
- **Login view** — updated to include phone/SMS/WhatsApp/Telegram CTA buttons.
- **Register view** — updated layout for progressive validation step UI.
- **UI Icon component** — expanded icon map to cover the new AuthChannel icons (`phone`, `chat`, `send`, `envelope`).
- **Language file** (`lang/en/auth.php`) — added translation keys for OTP send/verify flows.

### Configuration

- **DB connection** is set to PostgreSQL (`pgsql`) in `.env`, consistent with all existing migrations and other releases.
