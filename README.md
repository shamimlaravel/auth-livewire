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
│   │   ├── AuthChannel.php        # Auth delivery channels (SMS/WhatsApp/Telegram/Email)
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
│   │   │   ├── PhoneLogin.php       # Phone / SMS OTP login
│   │   │   ├── TelegramLogin.php    # Telegram OTP login
│   │   │   ├── WhatsAppLogin.php    # WhatsApp OTP login
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
│   │   │   ├── AdminAuthConfig.php  # Multi-channel auth & 7-provider OAuth configuration
│   │   │   ├── UserManager.php
│   │   │   └── RoleManager.php
│   │   └── Seller/             # Seller dashboard
│   │       └── Dashboard.php
│   │
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── AuthSetting.php        # Database-backed auth configuration key/value store
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
│   │   │   ├── MultiChannelOtpService.php  # Unified OTP delivery (SMS/WhatsApp/Telegram/Email)
│   │   │   ├── ProviderConfigService.php   # 7-provider OAuth config management
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
│               ├── Accordion.php       # Collapsible accordion UI widget
│               ├── Badge.php
│               ├── Button.php
│               ├── Card.php
│               ├── Dropdown.php
│               ├── Icon.php
│               ├── Input.php
│               ├── Modal.php
│               ├── SettingsRow.php     # Labeled settings row (label + input + description)
│               ├── SidebarLink.php
│               ├── Tabs.php            # Tabbed content switcher
│               └── Toggle.php          # On/off toggle switch
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

### Multi-Channel OTP Authentication Flow

```
GET  /login/phone         GET  /login/whatsapp        GET  /login/telegram
   │                         │                           │
   ▼                         ▼                           ▼
┌─────────┐              ┌────────┐               ┌──────────┐
│PhoneLogin│             │WhatsApp│               │Telegram  │
│Livewire │             │Login   │               │Login     │
└────┬────┘             └───┬────┘               └─────┬────┘
     │                     sendOtp()               sendOtp()
     ▼                         ▼                           ▼
┌────────────────────────────────────────────────────────────┐
│              MultiChannelOtpService::send()               │
│    generates SHA-256 token, invalidates old tokens         │
│    resolves delivery driver by channel from config         │
└──────────────┬──────────────────────────┬─────────────────┘
               │          │                │
               ▼          ▼                ▼
        ┌──────────┐  ┌──────────┐   ┌──────────┐
        │  Barta   │  │Devsfort  │   │Telegraph │
        │  SMS     │  │WhatsApp  │   │  Telegram│
        └────┬─────┘  └────┬─────┘   └────┬─────┘
             │             │               │
             ▼             ▼               ▼
        ┌──────────────────────────────────────────────┐
        │         User reads code on device             │
        └────────────────────┬─────────────────────────┘
                             │  enters code → verifyOtp()
                             ▼
        ┌──────────────────────────────────────────────┐
        │   MultiChannelOtpService::verify()            │
        │   hashes input, finds valid OtpToken record    │
        └──────────────┬───────────────────────────────┘
                       │
              ┌────────┴─────────┐
              │                  │
           valid              invalid
              │                  │
              ▼                  ▼
        ┌──────────┐       ┌──────────┐
        │ Auth::   │       │ Show     │
        │login()   │       │ error    │
        └──────────┘       └──────────┘
```

## Features

### Authentication Methods

| Method | Status | Description |
|--------|--------|-------------|
| Email/Password | ✅ | Traditional authentication with username or email |
| Phone / SMS OTP | ✅ | OTP-based login via SMS |
| WhatsApp OTP | ✅ | OTP-based login via WhatsApp |
| Telegram OTP | ✅ | OTP-based login via Telegram |
| Social Login | ✅ | OAuth with 7 providers — Google, GitHub, Facebook, Twitter, LinkedIn, GitLab, Microsoft |
| Magic Link | ✅ | Passwordless authentication via email |
| Two-Factor Auth | ✅ | TOTP-based 2FA with recovery codes; deliverable via SMS, WhatsApp, Telegram, or Email |
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

### Multi-Channel Authentication

- **AuthChannel Enum** — `App\Enums\AuthChannel` with `SMS`, `WhatsApp`, `Telegram`, and `Email` channels
- **Phone / SMS OTP** — Login or 2FA verification via one-time phone codes via Barta SMS driver
- **WhatsApp OTP** — Login or 2FA via WhatsApp messages via Devsfort/Whatsapp driver
- **Telegram OTP** — Login or 2FA via Telegram Bot via DefStudio/Telegraph driver
- **Email OTP** — Login or 2FA via Laravel mail notifications
- **MultiChannelOtpService** — unified `send()` / `verify()` with SHA-256 token hashing, expiration, and auto-invalidation of stale tokens
- **Flexible 2FA delivery** — Each user can choose their preferred 2FA channel per account
- **OtpToken model** — tracks `identifiable`, `channel`, `expires_at`, `used_at`, and `email` per token

### User Management

- **Role-Based Access**: User, Seller, and Admin roles
- **Profile Management**: Name, email, phone, address, and contact channels
- **Contact Channels**: Per-user phone, WhatsApp, and Telegram identifiers stored on the `users` table
- **Social Accounts**: Link multiple OAuth providers (7 supported)
- **Email Verification**: Required verification for new accounts

## Authentication Process

### Login Screen

The login interface provides a clean, secure entry point for users:

![Login Screen](screenshots/login-screen.png)

*Features:*
- Username or email input
- Password field with visibility toggle
- Phone / SMS OTP login button
- WhatsApp OTP login button
- Telegram OTP login button
- "Remember me" option
- Social login buttons (Google, GitHub, Facebook, Twitter, LinkedIn, GitLab, Microsoft)
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

# OAuth Providers (7 supported)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
GITLAB_CLIENT_ID=
GITLAB_CLIENT_SECRET=
MICROSOFT_CLIENT_ID=
MICROSOFT_CLIENT_SECRET=

# SMS / Phone OTP (Barta driver)
BARTA_SMS_API_KEY=
BARTA_SMS_SENDER=

# WhatsApp OTP (Devsfort driver)
DEVSFORT_WHATSAPP_TOKEN=

# Telegram OTP (DefStudio Telegraph driver)
TELEGRAM_BOT_TOKEN=

# Multi-Channel Auth Settings (overridden via Admin → Auth Config panel, stored in database)
# AUTH_OTP_EXPIRY=10          # OTP token expiry in minutes
# AUTH_OTP_CHANNEL=email      # Default OTP channel: sms | whatsapp | telegram | email
# AUTH_2FA_CHANNEL=email      # Default 2FA delivery channel: sms | whatsapp | telegram | email
# AUTH_2FA_FORCE_ALL=false    # Force 2FA for all users
# AUTH_PHONE_ENABLED=true
# AUTH_WHATSAPP_ENABLED=false
# AUTH_TELEGRAM_ENABLED=false
# AUTH_SOCIAL_GOOGLE=true
# AUTH_SOCIAL_GITHUB=true
# AUTH_SOCIAL_FACEBOOK=true
# AUTH_SOCIAL_TWITTER=false
# AUTH_SOCIAL_LINKEDIN=false
# AUTH_SOCIAL_GITLAB=false
# AUTH_SOCIAL_MICROSOFT=false

# Default Security
IP_WHITELIST_ENABLED=false
SUSPICIOUS_LOGIN_NOTIFICATION=true
```

### Multi-Channel Auth Configuration

Auth channels, OTP expiry, and provider toggles are centrally managed through the Admin panel at **Admin → Auth Config** (`AdminAuthConfig` Livewire component). Values are persisted in the `auth_settings` table via `AuthSetting::set()` and read back through `AuthSetting::get()` / `AuthSetting::isEnabled()`.

The `config/auth_channels.php` config file defines per-channel sub-configuration (SMS gateway, WhatsApp driver, Telegram bot token, etc.). Individual secrets and credentials can also be overridden per-channel in the **Admin → Auth Config** panel.

### OAuth Configuration

Configure OAuth providers in `config/services.php`:  
Provider callbacks now merge dynamic DB settings (via `ProviderConfigService`) with the static `config/services.php` values, so credentials can be managed from the Admin panel without code changes.

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
| POST | `/api/auth/verify-otp` | Verify OTP for two-factor authentication |
| POST | `/api/auth/two-factor/enable` | Generate 2FA secret |
| POST | `/api/auth/two-factor/confirm` | Confirm and enable 2FA |
| POST | `/api/auth/two-factor/disable` | Disable 2FA |
| GET | `/api/auth/two-factor/recovery-codes` | Generate recovery codes |

### Multi-Channel Auth Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/send-otp` | Send OTP via SMS, WhatsApp, Telegram, or Email |
| POST | `/api/auth/verify-otp-login` | Verify OTP and complete passwordless login |

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

**Send OTP Request (via SMS / WhatsApp / Telegram / Email):**
```json
POST /api/auth/send-otp
{
  "identifiable": "+1-555-000-0000",
  "channel": "sms"
}
```
`identifiable` is the phone number, WhatsApp number, Telegram chat ID, or email address depending on the chosen channel.  
Supported `channel` values: `sms`, `whatsapp`, `telegram`, `email`.

**Send OTP Response:**
```json
{
  "success": true,
  "message": "OTP sent successfully"
}
```

**Verify OTP Login Request:**
```json
POST /api/auth/verify-otp-login
{
  "identifiable": "+1-555-000-0000",
  "code": "123456"
}
```

**Verify OTP Login Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "token": "sanctum_token_here",
  "user": { "id": 1, "name": "John Doe" }
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
