<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('users can get token via API login', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success', 'message', 'data' => ['token', 'user'],
        ]);
});

test('API login fails with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
});

test('API login fails with missing fields', function () {
    $response = $this->postJson(route('api.auth.login'), []);

    $response->assertStatus(422);
});

test('users can register via API', function () {
    $response = $this->postJson(route('api.auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success', 'message', 'data' => ['token', 'user'],
        ]);
});

test('API registration fails with missing email', function () {
    $response = $this->postJson(route('api.auth.register'), [
        'name' => 'Test User',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422);
});

test('API registration fails with password mismatch', function () {
    $response = $this->postJson(route('api.auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
    ]);

    $response->assertStatus(422);
});

test('API forgot password sends reset link', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('api.auth.forgot-password'), [
        'email' => $user->email,
    ]);

    $response->assertStatus(200);
});

test('API forgot password fails with invalid email', function () {
    $response = $this->postJson(route('api.auth.forgot-password'), [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertStatus(422);
});

test('authenticated user can access protected route', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson(route('api.user'));

    $response->assertStatus(200);
});

test('unauthenticated user cannot access protected route', function () {
    $response = $this->getJson(route('api.user'));

    $response->assertStatus(401);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson(route('api.auth.logout'));

    $response->assertStatus(200);
});

test('authenticated user can enable 2FA via API', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson(route('api.auth.two-factor.enable'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success', 'data' => ['secret', 'qr_code_url', 'recovery_codes'],
        ]);
});

test('authenticated user can get recovery codes via API', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson(route('api.auth.two-factor.recovery-codes'));

    $response->assertStatus(200);
});
