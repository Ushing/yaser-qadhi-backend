<?php

namespace App\Query;

use App\Models\LectureSubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LectureSubCategoryQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(LectureSubCategory $lectureSubCategory)
    {
        $this->model = $lectureSubCategory;
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
            ->addColumn('action', function ($lectureSubCategory) use ($permission) {
                $actions = '';
                if ($permission->can('lecture-sub-category-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.lecture-sub-category.edit', [$lectureSubCategory->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Lecture Sub Category" data-title="Edit Lecture Sub Category"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('lecture-sub-category-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.lecture-sub-category.show', [$lectureSubCategory->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Lecture Sub Category Details" data-title="Lecture Sub Category Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('lecture-sub-category-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-lecture-sub-category-id="' . $lectureSubCategory->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }

                return $actions;
            })
            ->addColumn('status_change', function ($lectureSubCategory) {
                $toggleAction = '';
                if ($lectureSubCategory->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.lecture-sub-category.status', [$lectureSubCategory->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;

            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->editColumn('lecture_category_id', function ($lectureSubCategory) {
                return isset($lectureSubCategory->lectureCategory) ? $lectureSubCategory->lectureCategory->name : '--';
            })
            ->rawColumns(['action', 'status_change', 'lecture_category_id', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveLectureSubCategory($request): string
    {
        try {
            return $this->model->create([
                'name' => $request->name,
                'lecture_category_id' => $request->lecture_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateLectureSubCategory($request, $lectureSubCategory): string
    {
        try {
            return $lectureSubCategory->update([
                'name' => $request->name,
                'lecture_category_id' => $request->lecture_category_id,
                'status' => $request->status,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllLectureSubCategoryOfLectureCategoryId($id)
    {
        return $this->model->where('lecture_category_id', $id)->where('status', 1)->get();

    }

}
