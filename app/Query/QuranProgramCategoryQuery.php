<?php

namespace App\Query;

use App\Models\QuranProgramCategory;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class QuranProgramCategoryQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(QuranProgramCategory $quranProgramCategory)
    {
        $this->model = $quranProgramCategory;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $actions = '';
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.quran_program_category.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Quran Program Category" data-title="Edit Quran Program Category"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-tag-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveQuranProgramCategory($request): string
    {
        try {
            return $this->model->create([
                'title' => $request->title,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateQuranProgramCategory($request, $hajjCheckList): string
    {
        try {
            return $hajjCheckList->update([
                'title' => $request->title,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
