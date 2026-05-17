<?php

use App\Livewire\Seller\Dashboard as SellerDashboard;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('seller can view seller dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('seller');

    $response = $this->actingAs($user)->get(route('seller.dashboard'));

    $response->assertStatus(200);
});

test('reseller can view seller dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('reseller');

    $response = $this->actingAs($user)->get(route('seller.dashboard'));

    $response->assertStatus(200);
});

test('user cannot view seller dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('seller.dashboard'));

    $response->assertStatus(403);
});

test('admin cannot view seller dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get(route('seller.dashboard'));

    $response->assertStatus(403);
});

test('unauthhenticated user cannot view seller dashboard', function () {
    $response = $this->get(route('seller.dashboard'));

    $response->assertRedirect(route('login'));
});

test('seller dashboard Livewire component renders', function () {
    $user = User::factory()->create();
    $user->assignRole('seller');

    Livewire::actingAs($user)
        ->test(SellerDashboard::class)
        ->assertStatus(200);
});
