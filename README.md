<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Auth System

A comprehensive authentication system built with Laravel 13, featuring multi-factor authentication, social login, password security validation, and API support.

## Table of Contents

- [Project Overview](#project-overview)
- [Directory Structure](#directory-structure)
- [System Architecture](#system-architecture)
- [Response Flow](#response-flow)
- [Features](#features)
- [Authentication Process](#authentication-process)
- [Installation](#installation)
- [Configuration](#configuration)
- [API Documentation](#api-documentation)
- [Testing](#testing)

## Project Overview

This authentication system provides a secure, scalable foundation for user management with the following capabilities:

- **Multi-Factor Authentication (2FA)** - TOTP-based authentication using Google Authenticator
- **Social Login** - Google, GitHub, and Facebook OAuth integration
- **Password Security** - Breach detection, strength validation, and secure reset flows
- **Magic Link Authentication** - Passwordless login via email
- **Role-Based Access Control** - User, Seller, and Admin roles
- **API Authentication** - Sanctum token-based API authentication
- **Security Auditing** - Comprehensive login attempt and audit logging

## Directory Structure

```
auth/
├── app/
│   ├── Actions/
│   │   └── Auth/                 # Authentication actions
│   │       └── RedirectAuthenticatedUser.php
│   │
│   ├── DTOs/
│   │   ├── API/                  # API request/response DTOs
│   │   │   ├── ApiResponseDTO.php
│   │   │   └── TokenDTO.php
│   │   └── Auth/                 # Authentication DTOs
│   │       ├── LoginDTO.php
│   │       ├── RegisterDTO.php
│   │       └── SocialLoginDTO.php
│   │
│   ├── Enums/                    # Enum definitions
│   │   ├── AuditEvent.php
│   │   ├── AuthProvider.php
│   │   └── Role.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/            # API controllers
│   │   │   └── Auth/           # Web auth controllers
│   │   ├── Middleware/         # Custom middleware
│   │   │   ├── DeviceFingerprint.php
│   │   │   ├── IpWhitelist.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── TrackLoginSession.php
│   │   ├── Requests/           # Form request validation
│   │   └── Resources/          # API resources
│   │
│   ├── Livewire/               # Livewire components
│   │   ├── Auth/               # Authentication components
│   │   │   ├── Login.php
│   │   │   ├── Register.php
│   │   │   ├── TwoFactorChallenge.php
│   │   │   ├── MagicLinkRequest.php
│   │   │   ├── OtpLogin.php
│   │   │   ├── ForgotPassword.php
│   │   │   └── ResetPassword.php
│   │   ├── User/               # User dashboard
│   │   │   ├── AccountSettings.php
│   │   │   └── Dashboard.php
│   │   ├── Admin/              # Admin panel
│   │   │   ├── Dashboard.php
│   │   │   ├── UserManager.php
│   │   │   └── RoleManager.php
│   │   └── Seller/             # Seller dashboard
│   │       └── Dashboard.php
│   │
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── Profile.php
│   │   ├── Address.php
│   │   ├── SocialAccount.php
│   │   ├── LoginAttempt.php
│   │   ├── DeviceFingerprint.php
│   │   ├── MagicLinkToken.php
│   │   ├── OtpToken.php
│   │   ├── AuditLog.php
│   │   └── WhitelistedIp.php
│   │
│   ├── Notifications/          # Notification classes
│   │   ├── MagicLinkNotification.php
│   │   ├── OtpLoginNotification.php
│   │   └── SuspiciousLoginNotification.php
│   │
│   ├── Observers/              # Model observers
│   │   └── UserObserver.php
│   │
│   ├── Providers/              # Service providers
│   │   └── AppServiceProvider.php
│   │
│   ├── Rules/                  # Custom validation rules
│   │   └── CurrentPassword.php
│   │
│   ├── Services/
│   │   ├── API/                # API services
│   │   │   └── ApiTokenService.php
│   │   ├── Auth/               # Authentication services
│   │   │   ├── AuthenticationService.php
│   │   │   ├── MagicLinkService.php
│   │   │   ├── OtpService.php
│   │   │   ├── PasswordResetService.php
│   │   │   ├── PasswordValidationService.php
│   │   │   ├── SocialLoginService.php
│   │   │   └── TwoFactorService.php
│   │   └── Security/           # Security services
│   │       ├── AuditService.php
│   │       ├── DeviceFingerprintService.php
│   │       └── IpValidationService.php
│   │
│   └── View/
│       └── Components/         # Blade components
│           ├── Admin/App.php
│           ├── Seller/App.php
│           ├── User/App.php
│           └── Ui/
│               ├── Badge.php
│               ├── Button.php
│               ├── Card.php
│               ├── Dropdown.php
│               ├── Icon.php
│               ├── Input.php
│               ├── Modal.php
│               └── SidebarLink.php
│
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database schema
│   └── seeders/
│
├── resources/
│   ├── views/
│   │   ├── layouts/            # Blade layouts
│   │   ├── livewire/           # Livewire views
│   │   └── components/         # Blade components
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   ├── auth.php                # Auth routes
│   └── console.php             # Artisan commands
│
├── tests/
│   ├── Feature/
│   │   ├── Admin/
│   │   ├── Api/
│   │   ├── Auth/
│   │   ├── Profile/
│   │   └── Seller/
│   └── Unit/
```

## System Architecture

### Layer Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│  │   Livewire   │  │    Blade     │  │    API       │           │
│  │   Components │  │   Views      │  │   Routes     │           │
│  └──────────────┘  └──────────────┘  └──────────────┘           │
├─────────────────────────────────────────────────────────────────┤
│                         APPLICATION LAYER                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│  │   Services   │  │    DTOs      │  │   Actions    │           │
│  │              │  │              │  │              │           │
│  │ Authentication│  │ LoginDTO     │  │ RedirectUser │           │
│  │ TwoFactor    │  │ RegisterDTO  │  │              │           │
│  │ PasswordReset│  │ TokenDTO     │  │              │           │
│  │ OTP/MagicLink│  │              │  │              │           │
│  └──────────────┘  └──────────────┘  └──────────────┘           │
├─────────────────────────────────────────────────────────────────┤
│                           DOMAIN LAYER                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│  │    Models    │  │   Events     │  │ Exceptions   │           │
│  │              │  │              │  │              │           │
│  │ User         │  │ UserLoggedIn │  │ AuthException│           │
│  │ Profile      │  │ UserRegistered│  │ DeviceMismatch│          │
│  │ SocialAccount│  │              │  │ IpNotWhitelisted│        │
│  └──────────────┘  └──────────────┘  └──────────────┘           │
├─────────────────────────────────────────────────────────────────┤
│                         INFRASTRUCTURE                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│  │ Controllers  │  │ Middleware   │  │  Database    │           │
│  │              │  │              │  │              │           │
│  │ AuthController│  │ DeviceFingerp│  │ PostgreSQL   │           │
│  │ APIController │  │ IpWhitelist  │  │ Redis        │           │
│  │              │  │ RoleMiddleware│  │              │           │
│  └──────────────┘  └──────────────┘  └──────────────┘           │
└─────────────────────────────────────────────────────────────────┘
```

### Authentication Architecture

```
┌─────────────┐     ┌──────────────┐     ┌──────────────────┐
│   Client    │────▶│  Middleware  │────▶│   Controller     │
│             │     │  (Auth)      │     │                  │
└─────────────┘     └──────────────┘     └────────┬─────────┘
                                                    │
                                                    ▼
                                      ┌───────────────────────┐
                                      │    Service Layer      │
                                      │  ┌──────────────────┐ │
                                      │  │AuthenticationSvc │ │
                                      │  │TwoFactorService  │ │
                                      │  │PasswordResetSvc  │ │
                                      │  │OTP/MagicLinkSvc  │ │
                                      │  └──────────────────┘ │
                                      └───────────┬─────────────┘
                                                  │
                                      ┌───────────▼─────────────┐
                                      │     Model Layer         │
                                      │  ┌──────────────────┐   │
                                      │  │      User        │   │
                                      │  │   SocialAccount  │   │
                                      │  └──────────────────┘   │
                                      └─────────────────────────┘
```

## Response Flow

### Web Authentication Flow

```
┌──────────────┐
│  GET /login  │
└──────┬───────┘
       │
       ▼
┌──────────────────┐     ┌──────────────────┐
│  Login Livewire  │────▶│ Display Login Form │
│    Component     │     │                  │
└────────┬─────────┘     └──────────────────┘
         │
         │ submit()
         ▼
┌──────────────────┐
│Validate Credentials│
└────────┬─────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
  Valid    Invalid
    │         │
    ▼         ▼
┌──────────┐ ┌──────────┐
│Has 2FA?  │ │Add Error  │
└────┬─────┘ │Redirect   │
     │       └──────────┘
     │
  ┌──┴──┐
  │     │
  ▼     ▼
Yes   No
  │     │
  ▼     ▼
┌─────────────┐  ┌─────────────────┘
│Store User ID│  │ Auth::login()   │
│Show 2FA    │  │ Redirect to      │
│Challenge   │  │ Dashboard       │
└─────────────┘  └─────────────────┘
```

### Two-Factor Authentication Flow

```
┌──────────────────┐
│2FA Enabled User  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐     ┌──────────────────┐
│Generate Secret Key│────▶│ Generate QR Code │
│via TwoFactorSvc  │     │ via Google2FA    │
└──────────────────┘     └────────┬─────────┘
                                  │
                                  ▼
                        ┌──────────────────┐
                        │Display QR + Input│
                        │   Code Field     │
                        └────────┬─────────┘
                                 │
                                 │ verify code
                                 ▼
                        ┌──────────────────┐
                        │TwoFactorService  │
                        │verify(code,secret)│
                        └────────┬─────────┘
                                 │
                            ┌────┴────┐
                            │         │
                            ▼         ▼
                         Valid     Invalid
                            │         │
                            ▼         ▼
                    ┌─────────────┐ ┌─────────────┐
                    │Set confirmed │ │Show Error   │
                    │at timestamp  │ │            │
                    │Redirect      │ │            │
                    └─────────────┘ └─────────────┘
```

### Registration Flow

```
┌──────────────────┐
│  GET /register   │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐     Step 1: Email
│ Register Livewire │────▶ Validate & Check
│    Component      │     Email Uniqueness
└────────┬─────────┘
         │
         ▼
┌──────────────────┐     Step 2: Password
│Password Validation│────▶ Check Strength
│Real-time Checks  │     Breach Detection
└────────┬─────────┘
         │
         ▼
┌──────────────────┐     Step 3: Profile
│ Profile Details  │────▶ Collect Name, Phone,
│                   │     Address, Company
└────────┬─────────┘
         │
          ▼
┌──────────────────┐
│AuthenticationSvc │
│     register()   │
│   Create User    │
│   Assign Role    │
│   Create Profile │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Auth::login()   │
│   Redirect to    │
│    Dashboard     │
└──────────────────┘
```

### Password Reset Flow

```
┌──────────────────┐
│ Forgot Password  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ Enter Email      │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│PasswordResetController│
│sendResetLink()  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│Email with Reset  │
│     Token        │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│Click Reset Link  │
│                   │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ResetPassword Form│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│PasswordResetController│
│reset()           │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│Auto Login +      │
│Redirect to       │
│Dashboard         │
└──────────────────┘
```

### API Authentication Flow

```
┌──────────────────┐
│ POST /api/auth/login│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│AuthController@login│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│AuthenticationService│
│login() validate   │
│credentials        │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ApiTokenService    │
│issueTokenResponse │
│create Sanctum token│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ Return JSON:     │
│ { token, user }  │
└──────────────────┘
```

## Features

### Authentication Methods

| Method | Status | Description |
|--------|--------|-------------|
| Email/Password | ✅ | Traditional authentication with username or email |
| Social Login | ✅ | OAuth with Google, GitHub, Facebook |
| Magic Link | ✅ | Passwordless authentication via email |
| Two-Factor Auth | ✅ | TOTP-based 2FA with recovery codes |
| API Tokens | ✅ | Sanctum-based token authentication |

### Password Security

- **Strength Validation**: Real-time password strength checking with visual indicators
- **Breach Detection**: Integration with Have I Been Pwned API
- **Requirements**: Minimum 8 characters, uppercase, lowercase, number, and special character
- **Recovery Codes**: 8 single-use backup codes for 2FA

### Security Features

- **Device Fingerprinting**: Track and recognize trusted devices
- **IP Validation**: Detect suspicious login location changes
- **Audit Logging**: Comprehensive logging of authentication events
- **Rate Limiting**: Configurable throttling per endpoint
- **Account Status**: Active/inactive user management

### User Management

- **Role-Based Access**: User, Seller, and Admin roles
- **Profile Management**: Name, email, phone, and address
- **Social Accounts**: Link multiple OAuth providers
- **Email Verification**: Required verification for new accounts

## Authentication Process

### Login Screen

The login interface provides a clean, secure entry point for users:

![Login Screen](screenshots/login-screen.png)

*Features:*
- Username or email input
- Password field with visibility toggle
- "Remember me" option
- Social login buttons (Google, GitHub, Facebook)
- Magic link alternative
- Forgot password link

### Registration Process

Three-step registration with progressive validation:

**Step 1: Email & Username**
```
┌─────────────────────────────────────────┐
│  Email: [you@example.com        ]     │
│  Username: [johndoe             ]     │
│                                         │
│  [ Continue ]                           │
└─────────────────────────────────────────┘
```

**Step 2: Password**
```
┌─────────────────────────────────────────┐
│  Password: [••••••••••••••••••]       │
│  ┌───────────────────────────────────┐ │
│  │ ████████████████████░░░░░░░░░░ 80% │ │
│  └───────────────────────────────────┘ │
│  ✓ At least 8 characters               │
│  ✓ One uppercase letter                │
│  ✓ One lowercase letter                │
│  ✓ One number                          │
│  ○ One special character                │
│                                         │
│  Confirm Password: [••••••••••••••••] │
└─────────────────────────────────────────┘
```

**Step 3: Profile Details**
```
┌─────────────────────────────────────────┐
│  Full Name: [John Doe        ]           │
│  Phone: [+1 (555) 000-0000  ]           │
│                                         │
│  Address Line 1: [123 Main St   ]      │
│  City: [New York       ] State: [NY]    │
│  Postal Code: [10001    ] Country: [US]  │
│  Company: [Acme Inc.   ]                 │
│                                         │
│  [ Back ] [ Create Account ]            │
└─────────────────────────────────────────┘
```

### Two-Factor Authentication Setup

After clicking "Set Up 2FA" in account settings:

```
┌─────────────────────────────────────────┐
│  Two-Factor Authentication              │
│                                         │
│  Scan this QR code with your            │
│  authenticator app:                     │
│                                         │
│        ████████████████████████         │
│        ████████████████████████         │
│        ██  ██████  ████  ███████        │
│        ██████  ██████  ██  █████        │
│        █████████████████████████        │
│        ████████████████████████         │
│                                         │
│  Or enter this key manually:            │
│  JBSWY3DPEHPK3PXP                        │
│                                         │
│  Confirm with code from app:            │
│  [ 0 0 0 0 0 0         ]                │
│                                         │
│  [ Verify & Enable 2FA ]                │
└─────────────────────────────────────────┘
```

### Two-Factor Challenge

When 2FA is enabled, users see this screen after entering credentials:

```
┌─────────────────────────────────────────┐
│  Two-Factor Authentication              │
│                                         │
│  Enter the authentication code from     │
│  your authenticator app.                │
│                                         │
│           [ 0 0 0 0 0 0 ]               │
│                                         │
│  [ Verify ]                             │
└─────────────────────────────────────────┘
```

## Installation

```bash
# Clone the repository
git clone https://github.com/your-org/auth-system.git
cd auth-system

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed

# Build assets
npm run build

# Start development server
composer run dev
```

## Configuration

### Environment Variables

```env
# Application
APP_NAME="Auth System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=auth
DB_USERNAME=postgres
DB_PASSWORD=

# OAuth Providers
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=

# Security
IP_WHITELIST_ENABLED=false
SUSPICIOUS_LOGIN_NOTIFICATION=true
```

### OAuth Configuration

Configure OAuth providers in `config/services.php`:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => '/auth/google/callback',
],
```

## API Documentation

### Authentication Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/login` | Login with email/password |
| POST | `/api/auth/register` | Create new account |
| POST | `/api/auth/logout` | Revoke current token |
| POST | `/api/auth/forgot-password` | Send reset link |
| POST | `/api/auth/reset-password` | Reset password |
| POST | `/api/auth/verify-email/{id}/{hash}` | Verify email address |
| POST | `/api/auth/resend-verification` | Resend verification email |

### Two-Factor Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/two-factor/enable` | Generate 2FA secret |
| POST | `/api/auth/two-factor/confirm` | Confirm and enable 2FA |
| POST | `/api/auth/two-factor/disable` | Disable 2FA |
| GET | `/api/auth/two-factor/recovery-codes` | Generate recovery codes |

### Request/Response Examples

**Login Request:**
```json
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "SecurePassword123!"
}
```

**Login Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com"
    },
    "token": "sanctum_token_here"
  }
}
```

## Testing

```bash
# Run all tests
php artisan test

# Run feature tests
php artisan test --filter=Feature

# Run with coverage
php artisan test --coverage
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
