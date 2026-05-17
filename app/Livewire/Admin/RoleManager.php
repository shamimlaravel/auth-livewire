<?php

namespace App\Livewire\Admin;

use App\Enums\Role as RoleEnum;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    public ?int $editingRoleId = null;

    public string $roleName = '';

    public array $selectedPermissions = [];

    public function create(): void
    {
        $this->validate(['roleName' => 'required|string|max:255|unique:roles,name']);

        $role = Role::create(['name' => $this->roleName, 'guard_name' => 'web']);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        session()->flash('status', __('Role created successfully.'));
    }

    public function edit(int $id): void
    {
        $role = Role::findById($id);
        $this->editingRoleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function update(): void
    {
        $this->validate(['roleName' => 'required|string|max:255|unique:roles,name,'.$this->editingRoleId]);

        $role = Role::findById($this->editingRoleId);
        $role->update(['name' => $this->roleName]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        session()->flash('status', __('Role updated successfully.'));
    }

    public function delete(int $id): void
    {
        $role = Role::findById($id);

        if (in_array($role->name, [RoleEnum::Admin->value, RoleEnum::User->value])) {
            session()->flash('error', __('Cannot delete system roles.'));

            return;
        }

        $role->delete();
        session()->flash('status', __('Role deleted successfully.'));
    }

    public function resetForm(): void
    {
        $this->editingRoleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
    }

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'roles' => Role::with('permissions')->get(),
            'allPermissions' => Permission::all(),
        ])->layout('components.admin.app');
    }
}
