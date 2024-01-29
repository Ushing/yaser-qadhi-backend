<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permissions = [
            ['group_name' => 'dashboard', 'permissions' => ['dashboard.view']],
            ['group_name' => 'users', 'permissions' => ['users-create', 'users-view', 'users-edit', 'users-delete', 'users-status']],
            ['group_name' => 'role', 'permissions' => ['role-create', 'role-view', 'role-edit', 'role-delete',]],

            ['group_name' => 'dua-category', 'permissions' => ['dua-category-create', 'dua-category-view', 'dua-category-edit', 'dua-category-delete', 'dua-category-status']],
            ['group_name' => 'dua-sub-category', 'permissions' => ['dua-sub-category-create', 'dua-sub-category-view', 'dua-sub-category-edit', 'dua-sub-category-delete', 'dua-sub-category-status']],
            ['group_name' => 'dua', 'permissions' => ['dua-create', 'dua-view', 'dua-edit', 'dua-delete', 'dua-status']],

            ['group_name' => 'lecture-category', 'permissions' => ['lecture-category-create', 'lecture-category-view', 'lecture-category-edit', 'lecture-category-delete', 'lecture-category-status']],
            ['group_name' => 'lecture-sub-category', 'permissions' => ['lecture-sub-category-create', 'lecture-sub-category-view', 'lecture-sub-category-edit', 'lecture-sub-category-delete', 'lecture-sub-category-status']],
            ['group_name' => 'lecture', 'permissions' => ['lecture-create', 'lecture-view', 'lecture-edit', 'lecture-delete', 'lecture-status']],

            ['group_name' => 'customer-list', 'permissions' => [ 'customer-list-view','customer-list-delete',]],
            ['group_name' => 'subscription', 'permissions' => ['subscription-create', 'subscription-view', 'subscription-edit', 'subscription-delete', 'subscription-status']],
            ['group_name' => 'banner', 'permissions' => ['banner-create', 'banner-view', 'banner-edit', 'banner-delete', 'banner-status']],
            ['group_name' => 'event', 'permissions' => ['event-create', 'event-view', 'event-edit', 'event-delete', 'event-status']],
        ];

        // Do same for the admin guard
        $roleSuperAdmin = Role::create(
            ['name' => 'super-admin', 'guard_name' => 'web'],
        );

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup, 'guard_name' => 'web']);
                $roleSuperAdmin->givePermissionTo($permission);
                $permission->assignRole($roleSuperAdmin);
            }
        }

        // Assign super admin role permission to superadmin user
        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }

    }
}
