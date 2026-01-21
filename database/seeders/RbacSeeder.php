<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Permission\Infrastructure\Models\Permission;
use App\Modules\Role\Infrastructure\Models\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $adminApi = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminWeb = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $clientWeb = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // Permissions (web only for now; API is admin-only by role)
        $webPermissions = [
            // Roles
            'roles.read',
            'roles.create',
            'roles.update',
            'roles.delete',
            // Permissions
            'permissions.read',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            // Users (admin)
            'users.read',
            'users.create',
            'users.update',
            'users.delete',
            // Users (self)
            'users.self.read',
            'users.self.update',
        ];

        /** @var array<string, Permission> $permissions */
        $permissions = [];
        foreach ($webPermissions as $name) {
            $permissions[$name] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminWeb->permissions()->syncWithoutDetaching(array_map(static fn (Permission $p) => $p->id, $permissions));

        $clientWebPermissionIds = [
            $permissions['roles.read']->id,
            $permissions['permissions.read']->id,
            $permissions['users.self.read']->id,
            $permissions['users.self.update']->id,
        ];
        $clientWeb->permissions()->syncWithoutDetaching($clientWebPermissionIds);

        // NOTE: $adminApi role intentionally has no granular permissions for now.
        // API access is enforced as "admin only" by role on API controllers.
    }
}
