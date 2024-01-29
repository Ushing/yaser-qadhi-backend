<?php

namespace App\Query;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;


class UserQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');

        $userInfo = Auth::user()->id;
        $permission = Auth::user();
        return DataTables::of($query)
            ->addColumn('action', function ($row) use ($userInfo, $permission) {
                $actions = '';
                if ($permission->can('users-edit')) {
                    $actions .= '<a href="' . route('admin.users.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('users-delete')) {
                    if ($row->id != $userInfo) {
                        $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2 btn-delete"  data-users-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                    }
                }
                return $actions;
            })
            ->addColumn('role', function ($row) {
                foreach ($row->roles as $role) {

                    return '<span class="badge badge-info">' . ucwords($role->name) . '</span>';
                }

            })
            ->rawColumns(['action', 'role', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveUser($request): string
    {
        try {
            $query = $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($request->roles) {
                $user = $this->model->where('email', $request->email)->first();
                if ($user) {
                    $user->assignRole($request->roles);
                }
            }
            return $query;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateUser($request, $user): string
    {
        try {
            $query = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->detach();
            if ($request->roles) {
                $user->assignRole($request->roles);
            }
            return $query;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
