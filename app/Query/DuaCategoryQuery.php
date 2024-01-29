<?php

namespace App\Query;

use App\Models\DuaCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DuaCategoryQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(DuaCategory $duaCategory)
    {
        $this->model = $duaCategory;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $permission = Auth::user();
        $query = $this->model->orderBy('id');

        return DataTables::of($query)
            ->addColumn('action', function ($row) use ($permission) {
                $actions = '';
                if ($permission->can('dua-category-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.dua-category.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Dua Category" data-title="Edit Dua Category"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('dua-category-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.dua-category.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Dua Category Details" data-title="Dua Category Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('dua-category-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-dua-category-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })

            ->addColumn('status_change', function ($row) {
                $toggleAction = '';
                if ($row->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a href="' . route('admin.dua-category.status', [$row->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold ' . $color . ' btn-icon ml-2" title="' . $title . '" >' . $status . '</a>';
                return $toggleAction;

            })
            ->rawColumns(['action', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveDuaCategory($request): string
    {
        try {
            return $this->model->create([
                'name' => $request->name,
                'status' => $request->status ?? 1,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateDuaCategory($request, $duaCategory): string
    {
        try {
            return $duaCategory->update([
                'name' => $request->name,
                'status' => $request->status,
                'language_id' => 1,


            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
