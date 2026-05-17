<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->set('password', 'ValidP@ss1')
        ->set('passwordConfirmation', 'ValidP@ss1')
        ->call('nextStep')
        ->set('name', 'Test User')
        ->call('submit');

    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate with email', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('dashboard'));
});

test('users can authenticate with username', function () {
    $user = User::factory()->create(['username' => 'testuser']);
    $user->assignRole('user');

    Livewire::test(Login::class)
        ->set('login', 'testuser')
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('dashboard'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors('login');
});

test('users can logout', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');
});
