<?php

namespace App\Query;

use App\Models\SurahRecitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SurahRecitationQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(SurahRecitation $surahRecitation)
    {
        $this->model = $surahRecitation;
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
                $actions .= '<a href="' . route('admin.surah_recitations.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a href="' . route('admin.surah_recitations.show', [$row->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
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
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.surah_recitations.status', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })

            ->addColumn('add_files', function ($row) {
                $toggleAction = '';
                $color = 'btn-outline-primary';
                $tagText = 'Attach Files';
                $title = 'Click to Sub title and Srt Files';

                $toggleAction .= '<a title="' . $title . '"  class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" data-size="xl" data-url="' . route('admin.surahReciteFiles.create', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Add Files Of Surah Recitation" data-title="Add Files Of Surah Recitation"  href="#" >' . $tagText . '</a>';
                return $toggleAction;
            })


            ->rawColumns(['action', 'add_files', 'lecture_sub_category_id', 'description', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveSurahRecitation($request): string
    {
        try {
            if ($request->surah_audio != null) {
                $audioFileNameToStore = $request->surah_audio;
            } else {
                $audioFileNameToStore = null;
            }
            if ($request->surah_video != null) {
                $videoFileNameToStore = $request->surah_video;
            } else {
                $videoFileNameToStore = null;
            }
            return $this->model->create([
                'title' => $this->getSurahTitle($request->surah_id),
                'reference_id' => $request->reference_id ?? null,
                'surah_id' => $request->surah_id,
                'status' => $request->status ?? 1,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateSurahRecitation($request, $surahRecitation): string
    {
        try {
            if ($request->surah_audio != null) {
                $audioFileNameToStore = $request->surah_audio;
            } else {
                $audioFileNameToStore = $surahRecitation->audio;
            }
            if ($request->surah_video != null) {
                $videoFileNameToStore = $request->surah_video;
            } else {
                $videoFileNameToStore = $surahRecitation->video;
            }
            return $surahRecitation->update([
                'title' => $this->getSurahTitle($request->surah_id),
                'reference_id' => $request->reference_id ?? null,
                'surah_id' => $request->surah_id,
                'status' => $request->status ?? 1,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function getSurahTitle($id)
    {
        return DB::table('surahs')->where('id',$id)->first()->name_english;
    }


}
