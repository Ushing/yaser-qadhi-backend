<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\MessageBanner;
use App\Query\MessageBannerQuery;
use AWS\CRT\HTTP\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MessageBannerController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.banners.';

    public function __construct(MessageBannerQuery $messageBannerQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/banner';
        $this->query = $messageBannerQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('banner-view')) {
            abort(403, 'Sorry!! You are Unauthorized T !');
        }
        $data = [
            'moduleName' => 'Islamic Message Banner',
            'tableHeads' => ['Sr. No', 'Title', 'Image', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'image', 'name' => 'image'],

//                ['data' => 'position', 'name' => 'position'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'status_change', 'name' => 'status_change'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false],
            ],
        ];
        return view(self::moduleDirectory . 'index', $data);
    }

    public function getData(Request $request): JsonResponse
    {
        return $this->query->getAllData($request);
    }


    public function create(): View
    {
        if (is_null($this->user) or !$this->user->can('banner-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $data = ['moduleName' => 'Islamic Message Banner Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('banner-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $banner = $this->query->saveBanner($request);
        if ($banner) {
            alert()->success('Islamic Message Banner', 'Item Created Successfully');
            return redirect()->route('admin.banner.index');
        } else {
            alert()->error('Islamic Message Banner', 'Failed To Create');
            return redirect()->route('admin.banner.index');
        }
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('banner-view')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $banner = $this->query->find($id);
        $data = [
            'moduleName' => 'Islamic Message Banner Details',
            'banner' => $banner,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('banner-edit')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $banner = $this->query->find($id);
        $data = [
            'moduleName' => 'Islamic Message Banner Edit',
            'banner' => $banner,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(BannerRequest $request, MessageBanner $banner): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('banner-edit')) {
            abort(403, 'Sorry!! You are Unauthorized T !');
        }
        $banner = $this->query->updateBanner($request, $banner);
        if ($banner) {
            alert()->success('Islamic Message Banner', 'Item Updated Successfully');
            return redirect()->route('admin.banner.index');
        } else {
            alert()->error('Islamic Message Banner', 'Failed To Update');
            return redirect()->route('admin.banner.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('banner-delete')) {
            abort(403, 'Sorry!! You are Unauthorized T !');
        }
        $banner = $this->query->find($id);
        if ($banner->image) {
            Storage::disk('s3')->delete('banners/' . $banner->image);
        }
        $banner->delete();
        return response()->json(['status' => true, 'data' => $banner]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('banner-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $banner = $this->query->find($id);
        $status = $banner->status == 0 ? 1 : 0;
        $banner->update(['status' => $status]);
        if ($banner) {
            if ($banner->status == 1) {
                alert()->success('Islamic Message Banner', 'Item Status Is Active');
            }
            if ($banner->status == 0) {
                alert()->success('Islamic Message Banner', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Islamic Message Banner', 'Failed To Update Status');

        }
        return redirect()->route('admin.banner.index');
    }

    public function orderView():View
    {
        $banner =  MessageBanner::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('banner'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('messagebanners')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
    }
}
