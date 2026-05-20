<?php

use App\Models\AuthSetting;
use App\Models\User;
use App\Services\Auth\ProviderConfigService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    AuthSetting::query()->delete();
});

// ── ProviderConfigService ─────────────────────────────────────────────────────

test('provider config resolves defaults when no db settings exist', function () {
    Config::set('services.google.client_id', '');
    Config::set('services.google.client_secret', '');

    $config = app(ProviderConfigService::class)->getConfig('google');

    expect($config['enabled'])->toBeFalse();
    expect($config['client_id'])->toBe('');
    expect($config['redirect'])->toContain('/auth/google/callback');
});

test('provider config reads db settings when present', function () {
    AuthSetting::set('social_provider_google_enabled', 'true');
    AuthSetting::set('social_provider_google_client_id', 'db-client-id');
    AuthSetting::set('social_provider_google_client_secret', 'db-client-secret');
    AuthSetting::set('social_provider_google_scopes', 'openid,profile');

    $config = app(ProviderConfigService::class)->getConfig('google');

    expect($config['enabled'])->toBeTrue();
    expect($config['client_id'])->toBe('db-client-id');
    expect($config['scopes'])->toBe(['openid', 'profile']);
});

test('getAvailableProviders returns only enabled providers', function () {
    AuthSetting::set('social_provider_google_enabled', 'true');
    AuthSetting::set('social_provider_google_client_id', 'id');
    AuthSetting::set('social_provider_google_client_secret', 'secret');

    AuthSetting::set('social_provider_github_enabled', 'true');
    AuthSetting::set('social_provider_github_client_id', 'id');
    AuthSetting::set('social_provider_github_client_secret', 'secret');

    AuthSetting::set('social_provider_facebook_enabled', 'false');

    $available = app(ProviderConfigService::class)->getAvailableProviders();

    expect($available)->toBe(['google', 'github']);
    expect($available)->not->toContain('facebook');
});

test('disabled provider does not appear in available providers', function () {
    Config::set('services.google.client_id', '');
    Config::set('services.google.client_secret', '');

    $available = app(ProviderConfigService::class)->getAvailableProviders();

    expect($available)->toBe([]);
});

test('provider not enabled without client id and secret', function () {
    Config::set('services.google.client_id', '');
    Config::set('services.google.client_secret', '');

    AuthSetting::set('social_provider_google_enabled', 'true');

    $config = app(ProviderConfigService::class)->getConfig('google');

    expect($config['enabled'])->toBeFalse();
});

// ── Controller redirect ──────────────────────────────────────────────────────

test('redirect to enabled provider returns redirect response', function () {
    AuthSetting::set('social_provider_google_enabled', 'true');
    AuthSetting::set('social_provider_google_client_id', 'test-id');
    AuthSetting::set('social_provider_google_client_secret', 'test-secret');

    $driver = Mockery::mock(GoogleProvider::class);
    $driver->shouldReceive('scopes->redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));
    $driver->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $user = User::factory()->create()->assignRole('admin');

    $response = $this->actingAs($user)->get(route('auth.social.redirect', 'google'));

    expect($response->status())->toBe(302);
});

test('redirect to disabled provider returns 404', function () {
    AuthSetting::set('social_provider_google_enabled', 'false');
    AuthSetting::set('social_provider_google_client_id', '');
    AuthSetting::set('social_provider_google_client_secret', '');
    Config::set('services.google.client_id', '');
    Config::set('services.google.client_secret', '');

    $response = $this->get(route('auth.social.redirect', 'google'));

    $response->assertStatus(404);
});

test('redirect to unknown provider throws exception', function () {
    $response = $this->get(route('auth.social.redirect', 'unknown'));

    expect(in_array($response->status(), [404, 500]))->toBeTrue();
});

// ── Controller callback ──────────────────────────────────────────────────────

test('callback creates new user and social account', function () {
    $socialUser = Mockery::mock(Laravel\Socialite\Contracts\User::class);
    $socialUser->shouldReceive('getId')->andReturn('12345');
    $socialUser->shouldReceive('getName')->andReturn('Test User');
    $socialUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $socialUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');
    $socialUser->token = 'test-token';
    $socialUser->refreshToken = 'test-refresh-token';

    $driver = Mockery::mock(GoogleProvider::class);
    $driver->shouldReceive('user')->andReturn($socialUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $this->get(route('auth.social.callback', 'google'));

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Test User');
    expect($user->socialAccounts)->toHaveCount(1);
    expect($user->socialAccounts->first()->provider)->toBe('google');
    expect($user->socialAccounts->first()->provider_id)->toBe('12345');
});

test('callback links social account to existing user by email', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $socialUser = Mockery::mock(Laravel\Socialite\Contracts\User::class);
    $socialUser->shouldReceive('getId')->andReturn('67890');
    $socialUser->shouldReceive('getName')->andReturn('Existing User');
    $socialUser->shouldReceive('getEmail')->andReturn('existing@example.com');
    $socialUser->shouldReceive('getAvatar')->andReturn(null);
    $socialUser->token = 'test-token';
    $socialUser->refreshToken = 'test-refresh-token';

    $driver = Mockery::mock(GithubProvider::class);
    $driver->shouldReceive('user')->andReturn($socialUser);

    Socialite::shouldReceive('driver')->with('github')->andReturn($driver);

    $this->get(route('auth.social.callback', 'github'));

    $user = User::where('email', 'existing@example.com')->first();

    expect($user->id)->toBe($existingUser->id);
    expect($user->socialAccounts)->toHaveCount(1);
    expect($user->socialAccounts->first()->provider)->toBe('github');
});

test('callback reuses existing social account', function () {
    $user = User::factory()->create();
    $user->socialAccounts()->create([
        'provider' => 'facebook',
        'provider_id' => '11111',
    ]);

    $socialUser = Mockery::mock(Laravel\Socialite\Contracts\User::class);
    $socialUser->shouldReceive('getId')->andReturn('11111');
    $socialUser->shouldReceive('getName')->andReturn('Same User');
    $socialUser->shouldReceive('getEmail')->andReturn($user->email);
    $socialUser->shouldReceive('getAvatar')->andReturn(null);
    $socialUser->token = 'test-token';
    $socialUser->refreshToken = 'test-refresh-token';

    $driver = Mockery::mock(FacebookProvider::class);
    $driver->shouldReceive('user')->andReturn($socialUser);

    Socialite::shouldReceive('driver')->with('facebook')->andReturn($driver);

    $this->get(route('auth.social.callback', 'facebook'));

    expect($user->socialAccounts()->count())->toBe(1);
});

test('callback handles missing email gracefully', function () {
    $socialUser = Mockery::mock(Laravel\Socialite\Contracts\User::class);
    $socialUser->shouldReceive('getId')->andReturn('22222');
    $socialUser->shouldReceive('getName')->andReturn('No Email User');
    $socialUser->shouldReceive('getEmail')->andReturn(null);
    $socialUser->shouldReceive('getAvatar')->andReturn(null);
    $socialUser->token = 'test-token';
    $socialUser->refreshToken = 'test-refresh-token';

    $driver = Mockery::mock(GoogleProvider::class);
    $driver->shouldReceive('user')->andReturn($socialUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $this->get(route('auth.social.callback', 'google'));

    $user = User::where('auth_provider_id', '22222')->first();

    expect($user)->not->toBeNull();
    expect($user->email)->toContain('@');
});

// ── Social login page renders with providers ──────────────────────────────────

test('login page shows dynamic social provider buttons', function () {
    AuthSetting::set('social_provider_google_enabled', 'true');
    AuthSetting::set('social_provider_google_client_id', 'id');
    AuthSetting::set('social_provider_google_client_secret', 'secret');
    AuthSetting::set('social_provider_github_enabled', 'true');
    AuthSetting::set('social_provider_github_client_id', 'id');
    AuthSetting::set('social_provider_github_client_secret', 'secret');

    $response = $this->get(route('login'));

    $response->assertStatus(200);
    $response->assertSee('Google');
    $response->assertSee('GitHub');
});

test('login page hides social providers when none enabled', function () {
    Config::set('services.google.client_id', '');
    Config::set('services.google.client_secret', '');
    Config::set('services.github.client_id', '');
    Config::set('services.github.client_secret', '');
    Config::set('services.facebook.client_id', '');
    Config::set('services.facebook.client_secret', '');

    $response = $this->get(route('login'));

    $response->assertStatus(200);
    $response->assertDontSee('Or continue with');
});
