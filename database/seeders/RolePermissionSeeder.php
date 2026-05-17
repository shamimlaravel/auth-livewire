<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User management
            'view-users', 'create-users', 'edit-users', 'delete-users',
            // Role management
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            // Audit logs
            'view-audit-logs',
            // Products (for sellers/resellers)
            'view-products', 'create-products', 'edit-products', 'delete-products',
            // Orders
            'view-orders', 'create-orders', 'edit-orders', 'delete-orders',
            // Support tickets
            'view-tickets', 'respond-tickets', 'close-tickets',
            // Admin panel access
            'view-admin',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([]);

        $seller = Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        $seller->syncPermissions([
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-orders', 'create-orders', 'edit-orders',
        ]);

        $reseller = Role::firstOrCreate(['name' => 'reseller', 'guard_name' => 'web']);
        $reseller->syncPermissions([
            'view-products', 'view-orders', 'create-orders',
        ]);

        $support = Role::firstOrCreate(['name' => 'support_agent', 'guard_name' => 'web']);
        $support->syncPermissions([
            'view-users', 'view-tickets', 'respond-tickets', 'close-tickets',
        ]);
    }
}
