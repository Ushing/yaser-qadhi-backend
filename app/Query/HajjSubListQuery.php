<?php

namespace App\Query;

use App\Models\HajjChecklist;
use App\Models\HajjSublist;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class HajjSubListQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(HajjSublist $hajjSublist)
    {
        $this->model = $hajjSublist;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.hajj_sub_lists.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Hajj Sub List" data-title="Edit Hajj Sub List"  href="#" ><i class="fa fa-edit"></i></a>';
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
            ->editColumn('checklist_id', function ($row) {
                return HajjChecklist::where('id', $row->checklist_id)->first()->title ?? '--';
            })
            ->rawColumns(['action', 'checklist_id', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveHajjSubList($request): string
    {
        try {
            return $this->model->create([
                'title' => $request->title,
                'checklist_id' => $request->checklist_id,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateHajjSubList($request, $hajjSubList): string
    {
        try {
            return $hajjSubList->update([
                'title' => $request->title,
                'checklist_id' => $request->checklist_id,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
