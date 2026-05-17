<?php

use App\Livewire\Admin\RoleManager;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can view role manager page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.roles'));

    $response->assertStatus(200);
});

test('non-admin cannot view role manager', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('admin.roles'));

    $response->assertStatus(403);
});

test('admin can create a new role', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManager::class)
        ->set('roleName', 'editor')
        ->call('create');

    expect(Role::where('name', 'editor')->exists())->toBeTrue();
});

test('admin cannot create duplicate role', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManager::class)
        ->set('roleName', 'admin')
        ->call('create')
        ->assertHasErrors('roleName');
});

test('admin can edit a role', function () {
    $role = Role::create(['name' => 'custom_role', 'guard_name' => 'web']);

    Livewire::actingAs($this->admin)
        ->test(RoleManager::class)
        ->call('edit', $role->id)
        ->assertSet('editingRoleId', $role->id)
        ->assertSet('roleName', 'custom_role');
});

test('admin can delete a custom role', function () {
    $role = Role::create(['name' => 'temp_role', 'guard_name' => 'web']);

    Livewire::actingAs($this->admin)
        ->test(RoleManager::class)
        ->call('delete', $role->id);

    expect(Role::where('name', 'temp_role')->exists())->toBeFalse();
});

test('admin cannot delete system roles', function () {
    $adminRole = Role::where('name', 'admin')->first();

    Livewire::actingAs($this->admin)
        ->test(RoleManager::class)
        ->call('delete', $adminRole->id);

    expect(Role::where('name', 'admin')->exists())->toBeTrue();
});
