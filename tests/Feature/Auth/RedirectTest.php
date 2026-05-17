<?php

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Livewire\Auth\Login;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('admin redirects to /admin after login', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('admin.dashboard'));
});

test('seller redirects to /seller after login', function () {
    $user = User::factory()->create();
    $user->assignRole('seller');

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('seller.dashboard'));
});

test('reseller redirects to /seller after login', function () {
    $user = User::factory()->create();
    $user->assignRole('reseller');

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('seller.dashboard'));
});

test('user redirects to /dashboard after login', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(Login::class)
        ->set('login', $user->email)
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect(route('dashboard'));
});

test('RedirectAuthenticatedUser returns correct route for each role', function () {
    $action = app(RedirectAuthenticatedUser::class);

    $admin = User::factory()->create()->assignRole('admin');
    $seller = User::factory()->create()->assignRole('seller');
    $reseller = User::factory()->create()->assignRole('reseller');
    $user = User::factory()->create()->assignRole('user');

    expect($action->execute($admin))->toBe(route('admin.dashboard'));
    expect($action->execute($seller))->toBe(route('seller.dashboard'));
    expect($action->execute($reseller))->toBe(route('seller.dashboard'));
    expect($action->execute($user))->toBe(route('dashboard'));
});
