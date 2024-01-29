<?php

namespace App\Query;

use App\Models\DarsulHadith;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class DarsulHadithQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(DarsulHadith $DarsulHadith)
    {
        $this->model = $DarsulHadith;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id', 'asc');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $actions = '';
                $actions .= '<a href="' . route('admin.DarsulHadith_list.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a href="' . route('admin.DarsulHadith_list.show', [$row->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-surah-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
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
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.DarsulHadith_list.status', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })

            ->rawColumns(['action', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveRecitation($request): string
    {


        try {
            if ($request->quran_video != null) {
                $videoFileNameToStore = $request->quran_video;
            } else {
                $videoFileNameToStore = null;
            }

            if ($request->quran_audio != null) {
                $audioFileNameToStore = $request->quran_audio;
            } else {
                $audioFileNameToStore = null;
            }
            return $this->model->create([
                'title' =>$request->title,
                'reference_id' => $request->reference_id ?? null,
                'status' => $request->status ?? 1,
                'video' => $videoFileNameToStore,
                'audio' => $audioFileNameToStore,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateRecitation($request, $tahajjud_prayer): string
    {
        try {
            if ($request->quran_video != null) {
                $videoFileNameToStore = $request->quran_video;
            } else {
                $videoFileNameToStore = $tahajjud_prayer->video;
            }
            if ($request->quran_audio != null) {
                $audioFileNameToStore = $request->quran_audio;
            } else {
                $audioFileNameToStore = $tahajjud_prayer->audio;
            }

            return $tahajjud_prayer->update([
                'title' =>$request->title,
                'reference_id' => $request->reference_id ?? null,
                'status' => $request->status ?? 1,
                'video' => $videoFileNameToStore,
                'audio' => $audioFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
