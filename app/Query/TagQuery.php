<?php

namespace App\Query;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class TagQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Tag $tag)
    {
        $this->model = $tag;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.tag.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Tag" data-title="Edit Tag"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.tag.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Tag Details" data-title="Tag Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-tag-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->editColumn('type', function ($row) {
                if ($row->type == 'search'){
                    $badge = "badge badge-info fs-6";
                }else{
                    $badge = "badge badge-primary fs-6";

                }
                return '<span class="'.$badge.'">' . ucwords($row->type) . '</span>';

            })

            ->rawColumns(['action','type'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveTag($request): string
    {
        try {
            return $this->model->create([
                'name' => $request->name,
                'type' => $request->type,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateTag($request, $tag): string
    {
        try {
            return $tag->update([
                'name' => $request->name,
                'type' => $request->type,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
