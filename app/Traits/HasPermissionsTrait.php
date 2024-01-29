<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasPermissionsTrait{

    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

}
