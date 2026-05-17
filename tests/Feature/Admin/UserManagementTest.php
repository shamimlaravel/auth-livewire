<?php

use App\Livewire\Admin\UserManager;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can view user manager page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.users'));

    $response->assertStatus(200);
});

test('non-admin cannot view user manager', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('admin.users'));

    $response->assertStatus(403);
});

test('admin can see users list', function () {
    User::factory(3)->create()->each(fn ($u) => $u->assignRole('user'));

    Livewire::actingAs($this->admin)
        ->test(UserManager::class)
        ->assertSee(User::first()->name);
});

test('admin can search users', function () {
    User::factory()->create(['name' => 'Searchable User', 'email' => 'search@example.com'])
        ->assignRole('user');
    User::factory()->create(['name' => 'Other User', 'email' => 'other@example.com'])
        ->assignRole('user');

    Livewire::actingAs($this->admin)
        ->test(UserManager::class)
        ->set('search', 'Searchable')
        ->assertSee('Searchable User')
        ->assertDontSee('Other User');
});

test('admin can filter by role', function () {
    User::factory()->create(['name' => 'Seller User'])->assignRole('seller');
    User::factory()->create(['name' => 'Regular User'])->assignRole('user');

    Livewire::actingAs($this->admin)
        ->test(UserManager::class)
        ->set('roleFilter', 'seller')
        ->assertSee('Seller User')
        ->assertDontSee('Regular User');
});
