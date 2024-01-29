<?php

namespace App\Query;

use App\Models\YasirLecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Collection;

class YasirQadhiQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(YasirLecture $YasirLecture)
    {
        $this->model = $YasirLecture;
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
                $actions .= '<a href="' . route('admin.yasir-lecture.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-surah-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }



    public function saveYasirLecture($request): string
    {
        try {
            if ($request->hasFile('icon_image')) {
                $file = $request->file('icon_image');
                $iconImageFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('icon_image')->getClientOriginalExtension();
                $iconImageFileNameToStore = 'icon_image_' . time() . '_' . $iconImageFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $iconImageFileNameToStore);
            } else {
                $iconImageFileNameToStore = '';
            }

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $coverImageFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                $coverImageFileNameToStore = 'cover_image_' . time() . '_' . $coverImageFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $coverImageFileNameToStore);
            } else {
                $coverImageFileNameToStore = '';
            }

            return $this->model->create([
                'title' => $request->title,
                'icon_image' => $iconImageFileNameToStore,
                'cover_image' => $coverImageFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateYasirLecture($request, $YasirLecture): string
    {
        try {
            if ($request->hasFile('icon_image')) {
                $file = $request->file('icon_image');
                $iconImageFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('icon_image')->getClientOriginalExtension();
                $iconImageFileNameToStore = 'icon_image_' . time() . '_' . $iconImageFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $iconImageFileNameToStore);
            } else {
                $iconImageFileNameToStore = '';
            }

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $coverImageFileNameToStore = $file->getClientOriginalName();
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                $coverImageFileNameToStore = 'cover_image_' . time() . '_' . $coverImageFileNameToStore;
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $coverImageFileNameToStore);
            } else {
                $coverImageFileNameToStore = '';
            }

            return $YasirLecture->update([
                'title' => $request->title,
                'icon_image' => $iconImageFileNameToStore,
                'cover_image' => $coverImageFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllYasirLecture(): Collection|array
    {
        return YasirLecture::select(['id'])->orderBy('id', 'asc')->get();
    }
}
