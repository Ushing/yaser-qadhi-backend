<?php

namespace App\Query;

use App\Models\QuranRecitation;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class QuranRecitationQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(QuranRecitation $quranRecitation)
    {
        $this->model = $quranRecitation;
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
                $actions .= '<a href="' . route('admin.quran_recitations.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a href="' . route('admin.quran_recitations.show', [$row->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
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
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.quran_recitations.status', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })

//            ->addColumn('add_files', function ($row) {
//                $toggleAction = '';
//                $color = 'btn-outline-primary';
//                $tagText = 'Attach Files';
//                $title = 'Click to Sub title and Srt Files';
//
//                $toggleAction .= '<a title="' . $title . '"  class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" data-size="xl" data-url="' . route('admin.surahReciteFiles.create', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Add Files Of Surah Recitation" data-title="Add Files Of Surah Recitation"  href="#" >' . $tagText . '</a>';
//                return $toggleAction;
//            })


            ->rawColumns(['action', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveQuranRecitation($request): string
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

    public function updateQuranRecitation($request, $quranRecitation): string
    {
        try {
            if ($request->quran_video != null) {
                $videoFileNameToStore = $request->quran_video;
            } else {
                $videoFileNameToStore = $quranRecitation->video;
            }
            if ($request->quran_audio != null) {
                $audioFileNameToStore = $request->quran_audio;
            } else {
                $audioFileNameToStore = $quranRecitation->audio;
            }
            return $quranRecitation->update([
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
