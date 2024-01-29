<?php

namespace App\Query;

use App\Models\HajjProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HajjProcessQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(HajjProcess $hajjProcess)
    {
        $this->model = $hajjProcess;
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
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.hajj_processes.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Hajj Process" data-title="Edit Hajj Process"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-tag-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveHajjProcess($request): string
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $bannerFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'hajj_process_'. $bannerFileNameToStore . '_' . time() . '.' . $extension;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $bannerFileNameToStore);
            } else {
                $bannerFileNameToStore = '';
            }

            return $this->model->create([
                'process_no' => $request->process_no,
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'description' => $request->description,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateHajjProcess($request, $hajjProcess): string
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $bannerFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'hajj_process_'. $bannerFileNameToStore . '_' . time() . '.' . $extension;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $bannerFileNameToStore);
            } else {
                $bannerFileNameToStore = '';
            }

            return $hajjProcess->update([
                'process_no' => $request->process_no,
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'description' => $request->description,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
