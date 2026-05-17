<?php

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

test('wizard starts on step 1', function () {
    Livewire::test(Register::class)
        ->assertSet('step', 1);
});

test('step 1 validates email format', function () {
    Livewire::test(Register::class)
        ->set('email', 'not-an-email')
        ->call('nextStep')
        ->assertHasErrors('email');
});

test('step 1 detects existing email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    Livewire::test(Register::class)
        ->set('email', 'taken@example.com')
        ->assertSet('emailExists', true);
});

test('step 2 validates password criteria', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->set('password', 'weak')
        ->set('passwordConfirmation', 'weak')
        ->call('nextStep')
        ->assertHasErrors('password');
});

test('step 2 validates password confirmation mismatch', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->set('password', 'ValidP@ss1')
        ->set('passwordConfirmation', 'Mismatch1@')
        ->call('nextStep')
        ->assertHasErrors('passwordConfirmation');
});

test('step 2 password criteria updates in real time', function () {
    Livewire::test(Register::class)
        ->set('password', 'ValidP@ss1')
        ->assertSet('passwordScore', 5)
        ->assertSet('passwordLabel', 'Strong');
});

test('step 2 shows weak password', function () {
    Livewire::test(Register::class)
        ->set('password', 'short')
        ->assertSet('passwordScore', 1)
        ->assertSet('passwordLabel', 'Weak');
});

test('step 3 validates name is required', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->set('password', 'ValidP@ss1')
        ->set('passwordConfirmation', 'ValidP@ss1')
        ->call('nextStep')
        ->set('name', '')
        ->call('submit')
        ->assertHasErrors('name');
});

test('full wizard creates user with username and profile', function () {
    Livewire::test(Register::class)
        ->set('email', 'jane@example.com')
        ->set('username', 'jane_doe')
        ->call('nextStep')
        ->set('password', 'StrongP@ss1')
        ->set('passwordConfirmation', 'StrongP@ss1')
        ->call('nextStep')
        ->set('name', 'Jane Doe')
        ->set('phone', '+1234567890')
        ->set('address_line_1', '123 Main St')
        ->set('city', 'Portland')
        ->set('state', 'OR')
        ->set('postal_code', '97201')
        ->set('company', 'Acme Inc.')
        ->call('submit');

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->username)->toBe('jane_doe');
    expect($user->profile)->not->toBeNull();
    expect($user->profile->phone)->toBe('+1234567890');
    expect($user->profile->company)->toBe('Acme Inc.');
    expect($user->profile->primaryAddress)->not->toBeNull();
    expect($user->profile->primaryAddress->address_line_1)->toBe('123 Main St');
    expect($user->profile->primaryAddress->city)->toBe('Portland');
    expect($user->profile->primaryAddress->state)->toBe('OR');
    expect($user->profile->primaryAddress->postal_code)->toBe('97201');
    expect($user->profile->primaryAddress->is_primary)->toBeTrue();
});

test('full wizard redirects based on role', function () {
    Livewire::test(Register::class)
        ->set('email', 'redirect@example.com')
        ->call('nextStep')
        ->set('password', 'StrongP@ss1')
        ->set('passwordConfirmation', 'StrongP@ss1')
        ->call('nextStep')
        ->set('name', 'Redirect User')
        ->call('submit')
        ->assertRedirect();
});

test('user can go back from step 2 to step 1', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->call('previousStep')
        ->assertSet('step', 1);
});

test('user can go back from step 3 to step 2', function () {
    Livewire::test(Register::class)
        ->set('email', 'test@example.com')
        ->call('nextStep')
        ->set('password', 'ValidP@ss1')
        ->set('passwordConfirmation', 'ValidP@ss1')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->call('previousStep')
        ->assertSet('step', 2);
});

test('profile has correct default locale', function () {
    Livewire::test(Register::class)
        ->set('email', 'locale@example.com')
        ->call('nextStep')
        ->set('password', 'StrongP@ss1')
        ->set('passwordConfirmation', 'StrongP@ss1')
        ->call('nextStep')
        ->set('name', 'Locale User')
        ->call('submit');

    $user = User::where('email', 'locale@example.com')->first();
    expect($user->profile->locale)->toBe('en');
});

test('existing email blocks next step', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(Register::class)
        ->set('email', 'existing@example.com')
        ->call('nextStep')
        ->assertSet('step', 1)
        ->assertHasErrors('email');
});
