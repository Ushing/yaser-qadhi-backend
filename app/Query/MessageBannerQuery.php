<?php

namespace App\Query;

use App\Models\MessageBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MessageBannerQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(MessageBanner $messageBanner)
    {
        $this->model = $messageBanner;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');
        $permission = Auth::user();
        return DataTables::of($query)
            ->addColumn('action', function ($row) use ($permission) {
                $actions = '';
                if ($permission->can('banner-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.banner.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Islamic Message Banner" data-title="Edit Islamic Message Banner"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('banner-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.banner.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Islamic Message Banner Details" data-title="Islamic Message Banner Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('banner-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-banner-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })

            ->editColumn('status', function ($row) {
                return setStatus($row->status)  ;
            })

            ->editColumn('image', function ($row) {

               if (isset($row->image)){
                   $src = Storage::disk('s3')->url('banners/'.$row->image);
               }

                return '<img src="'.$src.'" style="width: 100px;">';
            })

            ->addColumn('status_change', function ($row) {
                $toggleAction ='';
                if ($row->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a href="' . route('admin.banner.status', [$row->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold ' . $color . ' btn-icon ml-2" title="'.$title.'" >'.$status.'</a>';
                return $toggleAction;

            })
            ->rawColumns(['action', 'image', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveBanner($request): string
    {
        try {

            if ($request->hasFile('image')) {
                $bannerFilenameWithExt = $request->file('image')->getClientOriginalName();
                $bannerFilename = pathinfo($bannerFilenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'islamic_banner_'. $bannerFilename . '_' . time() . '.' . $extension;
                $filePath = 'banners/' . $bannerFileNameToStore;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('image')));
            } else {
                $bannerFileNameToStore = '';
            }

            return $this->model->create([
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'status' => $request->status ?? 1,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateBanner($request, $banner): string
    {
        try {

            if ($request->hasFile('image')) {
                $bannerFilenameWithExt = $request->file('image')->getClientOriginalName();
                $bannerFilename = pathinfo($bannerFilenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $bannerFileNameToStore = 'islamic_banner_'. $bannerFilename . '_' . time() . '.' . $extension;
                $filePath = 'banners/' . $bannerFileNameToStore;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('image')));
                if (Storage::disk('s3')->exists('banners/' . $banner->image)) {
                    Storage::disk('s3')->delete('banners/' . $banner->image);
                }
            } else {
                $bannerFileNameToStore = '';
            }
            return $banner->update([
                'title' => $request->title,
                'image' => $bannerFileNameToStore,
                'status' => $request->status,
                'language_id' => 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
