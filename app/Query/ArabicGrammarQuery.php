<?php

namespace App\Query;

use App\Models\ArabicGrammar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Collection;

class ArabicGrammarQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(ArabicGrammar $ArabicGrammar)
    {
        $this->model = $ArabicGrammar;
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
                $actions .= '<a href="' . route('admin.arabic-grammar.edit', [$row->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-surah-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }



    public function saveArabicGrammar($request): string
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

    public function updateArabicGrammar($request, $ArabicGrammar): string
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

            return $ArabicGrammar->update([
                'title' => $request->title,
                'icon_image' => $iconImageFileNameToStore,
                'cover_image' => $coverImageFileNameToStore,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllArabicGrammar(): Collection|array
    {
        return ArabicGrammar::select(['id'])->orderBy('id', 'asc')->get();
    }
}
