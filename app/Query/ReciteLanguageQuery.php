<?php

namespace App\Query;

use App\Models\ReciteLanguage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ReciteLanguageQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(ReciteLanguage $reciteLanguage)
    {
        $this->model = $reciteLanguage;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.recite_languages.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Recite Language" data-title="Edit Recite Language"  href="#" ><i class="fa fa-edit"></i></a>';
                return $actions;
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status)  ;
            })

            ->addColumn('status_change', function ($row) {
                $toggleAction ='';
                if ($row->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';
                }
                $toggleAction .= '<a href="' . route('admin.recite_languages.status', [$row->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold ' . $color . ' btn-icon ml-2" title="'.$title.'" >'.$status.'</a>';
                return $toggleAction;

            })
            ->rawColumns(['action','status_change','status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveReciteLanguage($request): string
    {
        try {
            return $this->model->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateReciteLanguage($request, $reciteLanguage): string
    {
        try {
            return $reciteLanguage->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'status' => $request->status,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
