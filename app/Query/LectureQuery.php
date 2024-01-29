<?php

namespace App\Query;

use App\Models\Lecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class LectureQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Lecture $lecture)
    {
        $this->model = $lecture;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id', 'asc');
        $permission = Auth::user();

        return DataTables::of($query)
            ->addColumn('action', function ($lecture) use ($permission) {
                $actions = '';
                if ($permission->can('lecture-edit')) {
                    $actions .= '<a href="' . route('admin.lecture.edit', [$lecture->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('lecture-view')) {
                    $actions .= '<a href="' . route('admin.lecture.show', [$lecture->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('lecture-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-lecture-id="' . $lecture->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })
            ->addColumn('status_change', function ($lecture) {
                $toggleAction = '';
                if ($lecture->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.lecture.status', [$lecture->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })

            ->addColumn('add_tag', function ($lecture) {
                $toggleAction = '';
                $color = 'btn-outline-primary';
                $tagText = 'Attach Tags';
                $title = 'Click to add tags';

                $toggleAction .= '<a title="' . $title . '"  class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" data-size="lg" data-url="' . route('admin.tagDetails.create', [$lecture->id,'lecture']) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Add Tags of Lecture" data-title="Add Tags of Lecture"  href="#" >' . $tagText . '</a>';
                return $toggleAction;
            })
            ->editColumn('lecture_sub_category_id', function ($lecture) {
                return isset($lecture->lectureSubCategory) ? $lecture->lectureSubCategory->name : '--';
            })
            ->editColumn('description', function ($lecture) {
                return Str::limit(strip_tags($lecture->description), 30);
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->rawColumns(['action','add_tag', 'lecture_sub_category_id', 'description', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveLecture($request): string
    {
        try {
            if ($request->lecture_audio != null) {
                $audioFileNameToStore = $request->lecture_audio;
            } else {
                $audioFileNameToStore = null;
            }

            if ($request->lecture_video != null) {
                $videoFileNameToStore = $request->lecture_video;
            } else {
                $videoFileNameToStore = null;
            }

            return $this->model->create([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'lecture_sub_category_id' => $request->lecture_sub_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,
                'description' => $request->description,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateLecture($request, $lecture): string
    {

        try {
            if ($request->lecture_audio != null) {
                $audioFileNameToStore = $request->lecture_audio;
            } else {
                $audioFileNameToStore = $lecture->audio;
            }
            if ($request->lecture_video != null) {
                $videoFileNameToStore = $request->lecture_video;
            } else {
                $videoFileNameToStore = $lecture->video;
            }
            return $lecture->update([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'lecture_sub_category_id' => $request->lecture_sub_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,
                'description' => $request->description,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
