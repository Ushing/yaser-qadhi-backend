<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DuaCategoryRequest;
use App\Models\Dua;
use App\Models\DuaCategory;
use App\Query\DuaCategoryQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DuaCategoryController extends Controller
{

    protected DuaCategoryQuery $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.dua-category.';

    public function __construct(DuaCategoryQuery $duaCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/dua-category';
        $this->query = $duaCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('dua-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Dua Category !');
        }
        $data = [
            'moduleName' => 'Dua Category',
            'tableHeads' => ['Sr. No', 'Name', 'Position', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'position', 'name' => 'position'],
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
        if (is_null($this->user) or !$this->user->can('dua-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Dua Category !');
        }
        $data = ['moduleName' => 'Dua Category Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(DuaCategoryRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Dua Category !');
        }
        $duaCategory = $this->query->saveDuaCategory($request);
        if ($duaCategory) {
            alert()->success('Dua Category', 'Item Created Successfully');
        } else {
            alert()->error('Dua Category', 'Failed To Create');
        }
        return redirect()->route('admin.dua-category.index');
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('dua-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua Category !');
        }
        $duaCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Category Details',
            'duaCategory' => $duaCategory,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('dua-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua Category !');
        }
        $duaCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Category Edit',
            'duaCategory' => $duaCategory,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(DuaCategoryRequest $request, DuaCategory $duaCategory): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Dua Category !');
        }
        $updateDuaCategory = $this->query->updateDuaCategory($request, $duaCategory);
        if ($updateDuaCategory) {
            alert()->success('Dua Category', 'Item Updated Successfully');
        } else {
            alert()->error('Dua Category', 'Failed To Update');
        }
        return redirect()->route('admin.dua-category.index');
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-category-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Dua Category !');
        }
        $duaCategory = $this->query->find($id);
        $duaCategory->delete();
        return response()->json(['status' => true, 'data' => $duaCategory]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-category-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $duaCategory = $this->query->find($id);
        $status = $duaCategory->status == 0 ? 1 : 0;
        $duaCategory->update(['status' => $status]);
        if ($duaCategory) {
            if ($duaCategory->status == 1) {
                alert()->success('Dua Category', 'Item Status Is Active');
            }
            if ($duaCategory->status == 0) {
                alert()->success('Dua Category', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Dua Category', 'Failed To Update Status');

        }
        return redirect()->route('admin.dua-category.index');
    }

    public function orderView():View
    {
        $duaCategories =  DuaCategory::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('duaCategories'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('dua_categories')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
    }

}
