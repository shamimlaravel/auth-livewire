<?php

namespace App\Http\Controllers\API;

use App\DTOs\API\ApiResponseDTO;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ApiLoginRequest;
use App\Http\Requests\API\Auth\ApiRegisterRequest;
use App\Http\Requests\API\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\API\ApiTokenService;
use App\Services\Auth\AuthenticationService;
use App\Services\Auth\PasswordResetService;
use App\Services\Auth\TwoFactorService;
use App\Services\Security\DeviceFingerprintService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly ApiTokenService $apiTokenService,
        private readonly AuthenticationService $authenticationService,
        private readonly PasswordResetService $passwordResetService,
        private readonly TwoFactorService $twoFactorService,
        private readonly DeviceFingerprintService $deviceFingerprintService,
    ) {}

    public function login(ApiLoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginDTO::fromApiRequest($request);
            $user = $this->authenticationService->login($dto);

            $user->update([
                'last_login_ip' => $request->ip(),
                'last_login_at' => now(),
            ]);

            $result = $this->apiTokenService->issueTokenResponse($user);

            if ($fingerprint = $request->header('X-Device-Fingerprint')) {
                $this->deviceFingerprintService->record($user, $fingerprint);
            }

            return ApiResponseDTO::success(
                message: __('auth.login_success'),
                data: $result,
            );
        } catch (ValidationException $e) {
            return ApiResponseDTO::error(
                message: __('auth.failed'),
                errors: $e->errors(),
                code: 422,
            );
        }
    }

    public function register(ApiRegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterDTO::fromApiRequest($request);
            $user = $this->authenticationService->register($dto);

            $result = $this->apiTokenService->issueTokenResponse($user);

            return ApiResponseDTO::success(
                message: __('auth.register_success'),
                data: $result,
                code: 201,
            );
        } catch (ValidationException $e) {
            return ApiResponseDTO::error(
                message: __('auth.registration_failed'),
                errors: $e->errors(),
                code: 422,
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->apiTokenService->revokeCurrentToken($request->user());

        return ApiResponseDTO::success(message: __('auth.logout_success'));
    }

    public function user(Request $request): JsonResponse
    {
        try {
            return ApiResponseDTO::success(
                message: __('auth.user_retrieved'),
                data: $request->user()->toArray(),
            );
        } catch (\Throwable $e) {
            return ApiResponseDTO::error(
                message: __('An unexpected error occurred.'),
                code: 500,
            );
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $status = $this->passwordResetService->sendResetLink($request->validated('email'));

            return ApiResponseDTO::success(message: $status);
        } catch (ValidationException $e) {
            return ApiResponseDTO::error(
                message: __('auth.password_reset_failed'),
                errors: $e->errors(),
                code: 422,
            );
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $status = $this->passwordResetService->reset($request->validated());

            return ApiResponseDTO::success(message: $status);
        } catch (ValidationException $e) {
            return ApiResponseDTO::error(
                message: __('auth.password_reset_failed'),
                errors: $e->errors(),
                code: 422,
            );
        }
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return ApiResponseDTO::success(message: __('auth.email_already_verified'));
        }

        $user->markEmailAsVerified();

        return ApiResponseDTO::success(message: __('auth.email_verified'));
    }

    public function resendVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return ApiResponseDTO::success(message: __('auth.email_already_verified'));
        }

        $user->sendEmailVerificationNotification();

        return ApiResponseDTO::success(message: __('auth.verification_sent'));
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $this->twoFactorService->verify($user->two_factor_secret, $request->validated('code'))) {
            return ApiResponseDTO::error(
                message: __('auth.invalid_two_factor_code'),
                code: 422,
            );
        }

        return ApiResponseDTO::success(message: __('auth.two_factor_verified'));
    }

    public function enableTwoFactor(Request $request): JsonResponse
    {
        $user = $request->user();
        $secret = $this->twoFactorService->generateSecretKey();

        $user->update(['two_factor_secret' => $secret]);

        $qrCodeUrl = $this->twoFactorService->getQrCodeUrl(
            config('app.name'),
            $user->email,
            $secret,
        );

        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();
        $user->update(['two_factor_recovery_codes' => json_encode($recoveryCodes)]);

        return ApiResponseDTO::success(
            message: __('auth.two_factor_setup'),
            data: [
                'secret' => $secret,
                'qr_code_url' => $qrCodeUrl,
                'recovery_codes' => $recoveryCodes,
            ],
        );
    }

    public function confirmTwoFactor(VerifyOtpRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $this->twoFactorService->enable($user, $request->validated('code'))) {
            return ApiResponseDTO::error(
                message: __('auth.invalid_two_factor_code'),
                code: 422,
            );
        }

        return ApiResponseDTO::success(message: __('auth.two_factor_enabled'));
    }

    public function disableTwoFactor(Request $request): JsonResponse
    {
        $this->twoFactorService->disable($request->user());

        return ApiResponseDTO::success(message: __('auth.two_factor_disabled'));
    }

    public function recoveryCodes(Request $request): JsonResponse
    {
        $codes = $this->twoFactorService->generateRecoveryCodes();
        $request->user()->update(['two_factor_recovery_codes' => json_encode($codes)]);

        return ApiResponseDTO::success(
            message: __('auth.recovery_codes_generated'),
            data: ['recovery_codes' => $codes],
        );
    }
}
