<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('settings page can be rendered', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('settings'));

    $response->assertStatus(200);
});

test('unauthenticated user cannot access settings', function () {
    $response = $this->get(route('settings'));

    $response->assertRedirect(route('login'));
});
