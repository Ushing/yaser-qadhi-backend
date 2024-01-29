<?php

namespace App\Query;

use App\Models\DuaCategory;
use App\Models\DuaSubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DuaSubCategoryQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(DuaSubCategory $duaSubCategory)
    {
        $this->model = $duaSubCategory;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');
        $permission = Auth::user();

        return DataTables::of($query)
            ->addColumn('action', function ($duaSubCategory) use ($permission) {
                $actions = '';
                if ($permission->can('dua-sub-category-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.dua-sub-category.edit', [$duaSubCategory->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Dua Sub Category" data-title="Edit Dua Sub Category"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('dua-sub-category-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.dua-sub-category.show', [$duaSubCategory->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Dua Sub Category Details" data-title="Dua Sub Category Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('dua-sub-category-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-dua-sub-category-id="' . $duaSubCategory->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })
            ->addColumn('status_change', function ($duaSubCategory) {
                $toggleAction = '';
                if ($duaSubCategory->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.dua-sub-category.status', [$duaSubCategory->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;

            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->editColumn('dua_category_id', function ($duaSubCategory) {
                return isset($duaSubCategory->duaCategory) ? $duaSubCategory->duaCategory->name : '--';
            })
            ->rawColumns(['action', 'status_change', 'dua_category_id', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveDuaSubCategory($request): string
    {
        try {
            return $this->model->create([
                'name' => $request->name,
                'dua_category_id' => $request->dua_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateDuaSubCategory($request, $duaSubCategory): string
    {
        try {
            return $duaSubCategory->update([
                'name' => $request->name,
                'dua_category_id' => $request->dua_category_id,
                'status' => $request->status,
                'language_id' => 1,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllDuaSubCategoryOfDuaCategoryId($duaCategoryId)
    {
        return $this->model->where('dua_category_id', $duaCategoryId)->where('status', 1)->get();
    }


}
