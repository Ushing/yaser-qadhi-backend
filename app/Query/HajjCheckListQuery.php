<?php

namespace App\Query;

use App\Models\HajjChecklist;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class HajjCheckListQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(HajjChecklist $hajjChecklist)
    {
        $this->model = $hajjChecklist;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.hajj_check_lists.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Hajj Check List" data-title="Edit Hajj Check List"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-tag-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'true') {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-danger";

                }
                return '<span class="' . $badge . '">' . ucwords($row->status) . '</span>';

            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveHajjCheckList($request): string
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

    public function updateHajjCheckList($request, $hajjCheckList): string
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
