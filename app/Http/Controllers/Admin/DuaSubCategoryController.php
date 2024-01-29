<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DuaSubCategoryRequest;
use App\Models\DuaCategory;
use App\Models\DuaSubCategory;
use App\Query\DuaCategoryQuery;
use App\Query\DuaSubCategoryQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DuaSubCategoryController extends Controller
{

    protected DuaSubCategoryQuery $query;
    protected DuaCategoryQuery $duaCategoryQuery;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.dua-sub-category.';

    public function __construct(DuaSubCategoryQuery $duaSubCategoryQuery, DuaCategoryQuery $duaCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/dua-sub-category';
        $this->query = $duaSubCategoryQuery;
        $this->duaCategoryQuery = $duaCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Dua Sub Category !');
        }

        $data = [
            'moduleName' => 'Dua Sub Category',
            'tableHeads' => ['Sr. No', 'Name', 'Dua Category', 'Position', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'dua_category_id', 'name' => 'dua_category_id'],
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
        if (is_null($this->user) or !$this->user->can('dua-sub-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Dua Sub Category !');
        }
        $data = [
            'moduleName' => 'Dua Sub Category Create',
            'duaCategories' => $this->duaCategoryQuery->getActiveData()
        ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(DuaSubCategoryRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Dua Sub Category !');
        }
        $duaSubCategory = $this->query->saveDuaSubCategory($request);
        if ($duaSubCategory) {
            alert()->success('Dua Sub Category', 'Item Created Successfully');
        } else {
            alert()->error('Dua Sub Category', 'Failed To Create');
        }
        return redirect()->route('admin.dua-sub-category.index');
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua Sub Category !');
        }
        $duaSubCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Sub Category Details',
            'duaSubCategory' => $duaSubCategory,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua Sub Category !');
        }
        $duaSubCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Sub Category Edit',
            'duaCategories' => $this->duaCategoryQuery->getActiveData(),
            'duaSubCategory' => $duaSubCategory,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(DuaSubCategoryRequest $request, DuaSubCategory $duaSubCategory): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Dua Sub Category !');
        }
        $updateDuaSubCategory = $this->query->updateDuaSubCategory($request, $duaSubCategory);
        if ($updateDuaSubCategory) {
            alert()->success('Dua Sub Category', 'Item Updated Successfully');
        } else {
            alert()->error('Dua Sub Category', 'Failed To Update');
        }
        return redirect()->route('admin.dua-sub-category.index');
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Dua Sub Category !');
        }
        $duaSubCategory = $this->query->find($id);
        $duaSubCategory->delete();
        return response()->json(['status' => true, 'data' => $duaSubCategory]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-sub-category-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $duaSubCategory = $this->query->find($id);
        $status = $duaSubCategory->status == 0 ? 1 : 0;
        $duaSubCategory->update(['status' => $status]);
        if ($duaSubCategory) {
            if ($duaSubCategory->status == 1) {
                alert()->success('Dua Sub Category', 'Item Status Is Active');
            }
            if ($duaSubCategory->status == 0) {
                alert()->success('Dua Sub Category', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Dua Sub Category', 'Failed To Update Status');
        }
        return redirect()->route('admin.dua-sub-category.index');
    }

//    Fetch all dua subcategory by dua category id
    public function getDuaSubCategoryByDuaCategoryId(Request $request): JsonResponse
    {
        $duaSubCategories = [];
        if (!empty($request->duaCategoryId)) {
            $duaSubCategories = $this->query->getAllDuaSubCategoryOfDuaCategoryId($request->duaCategoryId);
        }
        return response()->json(['status' => true, 'duaSubCategories' => $duaSubCategories]);
    }

    public function orderView():View
    {
        $duaSubCategories =  DuaSubCategory::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('duaSubCategories'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('dua_sub_categories')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
    }

}
