<?php

namespace App\Livewire\Admin;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Services\Security\AuditService;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $roleFilter = null;

    public ?string $statusFilter = null;

    public ?int $editingUserId = null;

    public ?string $editingRole = null;

    public ?string $editingStatus = null;

    protected function queryString(): array
    {
        return ['search', 'roleFilter', 'statusFilter'];
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->editingRole = $user->roles->first()?->name;
        $this->editingStatus = $user->status;
    }

    public function saveUser(AuditService $auditService): void
    {
        $this->validate([
            'editingRole' => ['nullable', 'string', 'exists:roles,name'],
            'editingStatus' => ['required', 'string', 'in:active,suspended,banned'],
        ]);

        $user = User::findOrFail($this->editingUserId);

        $user->update(['status' => $this->editingStatus]);

        if ($this->editingRole) {
            $user->syncRoles([$this->editingRole]);
        }

        $auditService->log(
            event: AuditEvent::ProfileUpdated,
            user: $user,
            payload: [
                'status' => $this->editingStatus,
                'role' => $this->editingRole,
                'updated_by' => auth()->id(),
            ],
        );

        $this->editingUserId = null;
        $this->editingRole = null;
        $this->editingStatus = null;
        session()->flash('status', __('User updated successfully.'));
    }

    public function cancelEdit(): void
    {
        $this->editingUserId = null;
        $this->editingRole = null;
        $this->editingStatus = null;
    }

    public function render()
    {
        $query = User::with('roles');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.user-manager', [
            'users' => $query->latest()->paginate(15),
        ])->layout('components.admin.app', ['header' => 'User Management']);
    }
}
