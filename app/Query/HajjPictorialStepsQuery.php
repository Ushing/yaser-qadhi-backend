<?php

namespace App\Query;

use App\Models\HajjPictorialSteps;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HajjPictorialStepsQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(HajjPictorialSteps $hajjPictorialSteps)
    {
        $this->model = $hajjPictorialSteps;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.hajj_pictorial_steps.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Hajj Pictorial Step" data-title="Edit Hajj Pictorial Step"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-tag-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveHajjPictorialSteps($request): string
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $bannerFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'hajj_pictorial_steps_' . time() . '_' . $bannerFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $bannerFileNameToStore);
            } else {
                $bannerFileNameToStore = '';
            }

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $bannerFileNameToStoreVideo = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStoreVideo = 'hajj_pictorial_steps_' . time() . '_' . $bannerFileNameToStoreVideo;
                $destinationPath = public_path() . '/videos';
                $file->move($destinationPath, $bannerFileNameToStoreVideo);
            } else {
                $bannerFileNameToStore = '';
            }

            return $this->model->create([
                'step_no' => $request->step_no,
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'video' => $bannerFileNameToStoreVideo,
                'description' => $request->description,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateHajjPictorialStep($request, $hajjPictorialSteps): string
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $bannerFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'hajj_pictorial_steps_' . time() . '_' . $bannerFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $bannerFileNameToStore);
            } else {
                $bannerFileNameToStore = '';
            }

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $bannerFileNameToStoreVideo = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStoreVideo = 'hajj_pictorial_steps_' . time() . '_' . $bannerFileNameToStoreVideo;
                $destinationPath = public_path() . '/videos';
                $file->move($destinationPath, $bannerFileNameToStoreVideo);
            } else {
                $bannerFileNameToStoreVideo = '';
            }

            return $hajjPictorialSteps->update([
                'step_no' => $request->step_no,
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'video' => $bannerFileNameToStoreVideo,
                'description' => $request->description,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
