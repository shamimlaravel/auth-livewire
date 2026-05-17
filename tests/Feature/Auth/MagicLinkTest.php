<?php

use App\Livewire\Auth\MagicLinkRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('magic link request page can be rendered', function () {
    $response = $this->get(route('login.magic'));

    $response->assertStatus(200);
});

test('magic link can be requested', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(MagicLinkRequest::class)
        ->set('email', $user->email)
        ->call('submit')
        ->assertSet('status', __('auth.magic_link_sent'));
});

test('invalid magic link token returns error', function () {
    $response = $this->get(route('login.magic.verify', 'invalid-token'));

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
});
