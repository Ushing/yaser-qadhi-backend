<?php

namespace App\Query;

use App\Models\LectureCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LectureCategoryQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(LectureCategory $lectureCategory)
    {
        $this->model = $lectureCategory;
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
            ->addColumn('action', function ($row) use ($permission) {
                $actions = '';
                if ($permission->can('lecture-category-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.lecture-category.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Lecture Category" data-title="Edit Lecture Category"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('lecture-category-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.lecture-category.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Lecture Category Details" data-title="Lecture Category Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('lecture-category-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-lecture-category-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
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
                $toggleAction .= '<a href="' . route('admin.lecture-category.status', [$row->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold ' . $color . ' btn-icon ml-2" title="' . $title . '" >' . $status . '</a>';
                return $toggleAction;

            })
            ->rawColumns(['action', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveLectureCategory($request): string
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

    public function updateLectureCategory($request, $lectureCategory): string
    {
        try {
            return $lectureCategory->update([
                'name' => $request->name,
                'status' => $request->status,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
