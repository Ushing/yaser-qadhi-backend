<?php

namespace App\Query;

use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\DuaSubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DuaQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Dua $dua)
    {
        $this->model = $dua;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id','asc');
        $permission = Auth::user();

        return DataTables::of($query)
            ->addColumn('action', function ($dua) use ($permission) {
                $actions = '';
                if ($permission->can('dua-edit')) {
                    $actions .= '<a href="' . route('admin.dua.edit', [$dua->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('dua-view')) {
                    $actions .= '<a href="' . route('admin.dua.show', [$dua->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('dua-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-dua-id="' . $dua->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }

                return $actions;
            })
            ->addColumn('status_change', function ($dua) {
                $toggleAction = '';
                if ($dua->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.dua.status', [$dua->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })
            ->editColumn('dua_sub_category_id', function ($dua) {
                return isset($dua->duaSubCategory) ? $dua->duaSubCategory->name : '--';
            })
            ->editColumn('translation', function ($dua) {
                return Str::limit(strip_tags($dua->translation), 30);
            })
            ->editColumn('transliteration', function ($dua) {
                return Str::limit(strip_tags($dua->transliteration), 30);
            })

            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })

            ->addColumn('add_tag', function ($dua) {
                $toggleAction = '';
                     $color = 'btn-outline-primary';
                    $tagText = 'Attach Tags';
                    $title = 'Click to add tags';

                    $toggleAction .= '<a title="' . $title . '"  class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" data-size="lg" data-url="' . route('admin.tagDetails.create', [$dua->id,'dua']) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Add Tags of Dua" data-title="Add Tags of Dua"  href="#" >' . $tagText . '</a>';
                return $toggleAction;
            })

            ->rawColumns(['action', 'dua_sub_category_id', 'translation', 'transliteration', 'status_change', 'add_tag', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveDua($request): string
    {
        try {
            if ($request->dua_audio != null) {
                $audioFileNameToStore = $request->dua_audio;
            } else {
                $audioFileNameToStore = null;
            }

            if ($request->dua_video != null) {
                $videoFileNameToStore = $request->dua_video;
            } else {
                $videoFileNameToStore = null;
            }
            return $this->model->create([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'dua_sub_category_id' => $request->dua_sub_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,
                'translation' => $request->translation,
                'transliteration' => $request->transliteration,
                'arabic_dua' => $request->arabic_dua,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateDua($request, $dua): string
    {

        try {
            if ($request->dua_audio != null) {
                $audioFileNameToStore = $request->dua_audio;
            } else {
                $audioFileNameToStore = $dua->audio;
            }
            if ($request->dua_video != null) {
                $videoFileNameToStore = $request->dua_video;
            } else {
                $videoFileNameToStore = $dua->video;
            }
            return $dua->update([
                'title' => $request->title,
                'reference_id' => $request->reference_id ?? null,
                'dua_sub_category_id' => $request->dua_sub_category_id,
                'status' => $request->status ?? 1,
                'language_id' => 1,
                'translation' => $request->translation,
                'transliteration' => $request->transliteration,
                'arabic_dua' => $request->arabic_dua,
                'audio' => $audioFileNameToStore,
                'video' => $videoFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
