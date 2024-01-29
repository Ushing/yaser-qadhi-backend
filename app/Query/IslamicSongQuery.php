<?php

namespace App\Query;

use App\Models\IslamicSong;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class IslamicSongQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(IslamicSong $islamicSong)
    {
        $this->model = $islamicSong;
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
                $actions .= '<a href="' . route('admin.islamic_songs.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a href="' . route('admin.islamic_songs.show', [$row->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
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
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.islamic_songs.status', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })

            ->addColumn('add_files', function ($row) {
                $toggleAction = '';
                $color = 'btn-outline-primary';
                $tagText = 'Attach Files';
                $title = 'Click to Sub title and Srt Files';

                $toggleAction .= '<a title="' . $title . '"  class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" data-size="xl" data-url="' . route('admin.quranProgramFiles.create', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Add Files Of Quran Program" data-title="Add Files Of Quran Program"  href="#" >' . $tagText . '</a>';
                return $toggleAction;
            })
            ->rawColumns(['action', 'add_files', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveIslamicSong($request): string
    {
        try {
            if ($request->quran_audio != null) {
                $audioFileNameToStore = $request->quran_audio;
            } else {
                $audioFileNameToStore = null;
            }
            if ($request->quran_video != null) {
                $videoFileNameToStore = $request->quran_video;
            } else {
                $videoFileNameToStore = null;
            }
            return $this->model->create([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'status' => $request->status ?? 1,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateIslamicSong($request, $islamicSong): string
    {
        try {
            if ($request->quran_audio != null) {
                $audioFileNameToStore = $request->quran_audio;
            } else {
                $audioFileNameToStore = $islamicSong->audio;
            }
            if ($request->quran_video != null) {
                $videoFileNameToStore = $request->quran_video;
            } else {
                $videoFileNameToStore = $islamicSong->video;
            }
            return $islamicSong->update([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'status' => $request->status ?? 1,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



}
