# Authentication System Documentation

## Architecture Overview

```
routes/web.php          → Public pages (welcome, dashboard)
routes/auth.php         → Web auth (session-based, Livewire)
routes/api.php          → API auth (Sanctum token-based)
```

### Auth Flow Diagram

```
Web (Session):
┌─────────┐   ┌──────────┐   ┌──────────┐   ┌───────────┐
│ Browser │ → │ Livewire │ → │ Services │ → │ Database  │
│         │ ← │ Component│ ← │ /Actions │ ← │ /Models   │
└─────────┘   └──────────┘   └──────────┘   └───────────┘

API (Token):
┌────────┐   ┌──────────┐   ┌──────────┐   ┌───────────┐
│ Client │ → │ API Auth │ → │ Services │ → │ Database  │
│        │ ← │Controller│ ← │ /Actions │ ← │ /Models   │
└────────┘   └──────────┘   └──────────┘   └───────────┘
```

---

## API Endpoints

All API endpoints are prefixed with `/api/`.

### Authentication

#### POST `/api/auth/register`

Create a new user account.

**Request:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Account created successfully.",
    "data": {
        "token": {
            "access_token": "1|abc123...",
            "token_type": "Bearer",
            "expires_in": null
        },
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "avatar": null,
            "phone": null,
            "status": "active",
            "email_verified": false,
            "two_factor_enabled": false,
            "auth_provider": "email",
            "last_login_ip": null,
            "last_login_at": null,
            "roles": ["user"],
            "permissions": []
        }
    },
    "meta": {
        "timestamp": "2026-05-17T12:00:00+00:00"
    }
}
```

#### POST `/api/auth/login`

Authenticate with email and password.

**Request:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Headers (optional):**
- `X-Device-Fingerprint`: SHA-256 device fingerprint hash
- `X-Platform`: Platform name (e.g., "iOS", "Android", "Web")

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged in.",
    "data": {
        "token": { "access_token": "2|def456...", "token_type": "Bearer" },
        "user": { ... }
    }
}
```

#### POST `/api/auth/logout`

Revoke the current API token. Requires `Authorization: Bearer` header.

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out."
}
```

#### POST `/api/auth/forgot-password`

Send a password reset link to the email.

**Request:**
```json
{
    "email": "john@example.com"
}
```

#### POST `/api/auth/reset-password`

Reset the password using the token from the email.

**Request:**
```json
{
    "token": "reset-token-from-email",
    "email": "john@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

#### POST `/api/auth/verify-email`

Verify the authenticated user's email (requires Sanctum token).

**Response (200):**
```json
{
    "success": true,
    "message": "Email verified successfully."
}
```

#### POST `/api/auth/resend-verification`

Resend the email verification notification.

### Two-Factor Authentication (2FA)

All 2FA endpoints require authentication via Sanctum.

#### POST `/api/auth/two-factor/enable`

Generate a 2FA secret and QR code URL.

**Response (200):**
```json
{
    "success": true,
    "message": "Scan the QR code with your authenticator app...",
    "data": {
        "secret": "JBSWY3DPEHPK3PXP",
        "qr_code_url": "otpauth://totp/App:john@example.com?secret=...",
        "recovery_codes": ["abc123-def456", ...]
    }
}
```

#### POST `/api/auth/two-factor/confirm`

Confirm 2FA setup by verifying a code.

**Request:**
```json
{
    "code": "123456"
}
```

#### POST `/api/auth/two-factor/disable`

Disable 2FA for the authenticated user.

#### GET `/api/auth/two-factor/recovery-codes`

Get new recovery codes.

### User

#### GET `/api/user`

Get the authenticated user's profile. Requires `Authorization: Bearer` header.

---

## Security Features

### Rate Limiting

| Limiter | Rate | Applied To |
|---------|------|------------|
| `auth` | 5 requests/min | Login, Register, Forgot Password |
| `api` | 60 requests/min | All authenticated API routes |
| `magic-link` | 2 requests/min | Magic link requests |

### IP Whitelisting

Middleware `ip-whitelist` checks `whitelisted_ips` table. Returns 403 if IP not whitelisted.

### Device Fingerprinting

- Client sends `X-Device-Fingerprint` header (SHA-256 hash of browser, OS, screen, etc.)
- Middleware `device-fingerprint` records new fingerprints
- Audit log entry for unknown devices

### Session IP Validation

- `TrackLoginSession` middleware compares current IP vs `last_login_ip`
- On IP change: audit log entry created, suspicious login email sent to user
- **Warning only** — session is NOT invalidated immediately

### Audit Logging

All auth events logged to `audit_logs` table:

| Event | Description |
|-------|-------------|
| `registered` | New user registered |
| `logged_in` | Successful login |
| `logged_out` | User logged out |
| `login_failed` | Failed login attempt |
| `password_reset` | Password was reset |
| `ip_changed` | IP address changed |
| `suspicious_login` | Suspicious activity detected |
| `magic_link_requested` | Magic link email sent |
| `two_factor_enabled` | 2FA was enabled |

---

## Role & Permission System

### Roles (pre-seeded)

| Role | Permissions |
|------|------------|
| **Admin** | All permissions |
| **User** | Basic profile access |
| **Seller** | Manage products and orders |
| **Reseller** | View products and create orders |
| **Support Agent** | View users and manage tickets |

### Admin CRUD

Access `/admin/roles` to manage roles and permissions via Livewire UI.

---

## Web Routes (Livewire)

| URL | Component | Auth | Description |
|-----|-----------|------|-------------|
| `/login` | `Login` | Guest | Email/password login |
| `/register` | `Register` | Guest | User registration |
| `/forgot-password` | `ForgotPassword` | Guest | Request password reset |
| `/reset-password/{token}` | `ResetPassword` | Guest | Reset password form |
| `/login/magic` | `MagicLinkRequest` | Guest | Request magic link |
| `/login/magic/{token}` | `MagicLinkController` | Guest | Verify magic link |
| `/auth/{provider}/redirect` | `SocialLoginController` | Guest | Social login redirect |
| `/dashboard` | `Dashboard` | Auth+Verified | User dashboard |
| `/admin/roles` | `RoleManager` | Auth+Admin | Role management |
| `/logout` | POST | Auth | Logout |
| `/email/verify` | `EmailVerificationController` | Auth | Email verification |

---

## Directory Structure

```
app/
├── Actions/                  # Single-responsibility action classes
│   ├── Auth/                 # Register, Login, SendMagicLink, VerifyEmail
│   └── Security/             # LogAuthenticationAttempt, ValidateDeviceFingerprint
├── DTOs/                     # Data Transfer Objects
│   ├── API/                  # ApiResponseDTO, TokenDTO
│   ├── Auth/                 # LoginDTO, RegisterDTO, MagicLinkDTO, etc.
│   └── User/                 # UserDTO
├── Enums/                    # Role, AuthProvider, LoginStatus, AuditEvent
├── Events/                   # UserRegistered, UserLoggedIn, SuspiciousLoginDetected
├── Exceptions/               # AuthenticationException, IpNotWhitelistedException
├── Http/
│   ├── Controllers/          # Auth/, API/, Admin/ controllers
│   ├── Middleware/           # IpWhitelist, DeviceFingerprint, TrackLoginSession, RoleMiddleware
│   ├── Requests/             # Auth/, API/Auth/ form requests
│   └── Resources/            # UserResource, AuthResource
├── Livewire/                 # Livewire 4 components
│   ├── Auth/                 # Login, Register, ForgotPassword, etc.
│   ├── Admin/                # RoleManager
│   └── Components/           # AppLayout
├── Models/                   # User, SocialAccount, LoginAttempt, etc.
├── Notifications/            # MagicLinkNotification, SuspiciousLoginNotification
├── Observers/                # UserObserver (IP change detection)
├── Rules/                    # CurrentPassword, DeviceFingerprintRule
└── Services/
    ├── API/                  # ApiTokenService
    ├── Auth/                 # AuthenticationService, MagicLinkService, etc.
    └── Security/             # AuditService, DeviceFingerprintService, IpValidationService
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 401 | Unauthenticated — login required |
| 403 | Forbidden — insufficient permissions or IP not whitelisted |
| 422 | Validation error — invalid input |
| 429 | Too many requests — rate limit exceeded |

---

## Testing

```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test --compact --filter=AuthenticationTest

# Run API tests
php artisan test --compact --filter=ApiAuthenticationTest
```
