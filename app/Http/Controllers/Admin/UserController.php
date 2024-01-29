<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Query\UserQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.users.';

    public function __construct(UserQuery $userQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/users';
        $this->query = $userQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('users-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Users !');
        }
        $data = [
            'moduleName' => 'Lists Of User',
            'tableHeads' => ['Sr. No', 'Name', 'Email', 'Role', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'role', 'name' => 'role'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false],
            ],
        ];
        return view(self::moduleDirectory . 'index', $data);
    }

    public function getData(Request $request): JsonResponse
    {
        return $this->query->getAllData($request);
    }

    public function create(): View
    {
        if (is_null($this->user) or !$this->user->can('users-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Users !');
        }
        $data = [
            'moduleName' => 'User Create',
            'roles'=> Role::all(),
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(UserRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('users-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Users !');
        }
        $users = $this->query->saveUser($request);
        if ($users) {
            alert()->success('User', 'Item Created Successfully');
        } else {
            alert()->error('User', 'Failed To Create');
        }
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View
    {
        if (is_null($this->user) or !$this->user->can('users-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Edit Users !');
        }
        $users = $this->query->find($id);
        $data = [
            'moduleName' => 'User Details',
            'users' => $users,
            'roles'=> Role::all(),
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('users-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Edit Users !');
        }
        $updateUser = $this->query->updateUser($request, $user);
        if ($updateUser) {
            alert()->success('User', 'Item Updated Successfully');
        } else {
            alert()->error('User', 'Failed To Update');
        }
        return redirect()->route('admin.users.index');
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('users-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Users !');
        }
        $users = $this->query->find($id);
        $users->delete();
        return response()->json(['status' => true, 'data' => $users]);
    }
}
