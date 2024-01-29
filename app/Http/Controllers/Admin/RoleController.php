<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{


    public $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    public function index():View
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create():View
    {
        if (is_null($this->user) or !$this->user->can('role-create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        $all_permissions  = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.roles.create', compact('all_permissions', 'permission_groups'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user) or !$this->user->can('role-create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
          $per=  $role->syncPermissions($permissions);
        }
        if ($role or  $per){
            alert()->success('Roles Module', 'Item Created Successfully');
            return redirect()->route('admin.roles.index');
        } else {
                alert()->error('Roles Module', 'Failed To Create');
                return redirect()->route('admin.roles.index');
            }
        }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View
    {
        if (is_null($this->user) or !$this->user->can('role-edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }
        $role = Role::findById($id, 'web');
        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.roles.edit', compact('role', 'all_permissions', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('role-edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }
        if ($id === 1) {
            alert()->error('Sorry !! You are not authorized to edit this role !');
            return redirect()->route('admin.roles.index');
        }

        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);
        $role = Role::findById($id, 'web');
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }
        alert()->success('Roles Module', 'Item Updated Successfully');
        return redirect()->route('admin.roles.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('role-delete')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        $role = Role::findOrFail($id);
       $res= $role->delete();
        if ($res) {
            alert()->success('Role Delete','Success');
            return redirect()->route('admin.roles.index');
        } else {
            alert()->error('Role Delete','Failed');
            return redirect()->route('admin.roles.index');
        }
    }
}
