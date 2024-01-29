<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DuaCategoryRequest;
use App\Http\Requests\LectureCategoryRequest;
use App\Models\DuaCategory;
use App\Models\LectureCategory;
use App\Query\DuaCategoryQuery;
use App\Query\LectureCategoryQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LectureCategoryController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.lecture-category.';

    public function __construct(LectureCategoryQuery $lectureCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/lecture-category';
        $this->query = $lectureCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Lecture Category !');
        }
        $data = [
            'moduleName' => 'Lecture Category',
            'tableHeads' => ['Sr. No', 'Name', 'Position', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
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
        if (is_null($this->user) or !$this->user->can('lecture-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Lecture Category !');
        }
        $data = ['moduleName' => 'Lecture Category Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(LectureCategoryRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Lecture Category !');
        }
        $lectureCategory = $this->query->saveLectureCategory($request);
        if ($lectureCategory) {
            alert()->success('Lecture Category', 'Item Created Successfully');
            return redirect()->route('admin.lecture-category.index');
        } else {
            alert()->error('Lecture Category', 'Failed To Create');
            return redirect()->route('admin.lecture-category.index');
        }
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture Category !');
        }
        $lectureCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Category Details',
            'lectureCategory' => $lectureCategory,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture Category !');
        }
        $lectureCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Category Edit',
            'lectureCategory' => $lectureCategory,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(LectureCategoryRequest $request, LectureCategory $lectureCategory): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Lecture Category !');
        }
        $updateLectureCategory = $this->query->updateLectureCategory($request, $lectureCategory);
        if ($updateLectureCategory) {
            alert()->success('Lecture Category', 'Item Updated Successfully');
            return redirect()->route('admin.lecture-category.index');
        } else {
            alert()->error('Lecture Category', 'Failed To Update');
            return redirect()->route('admin.lecture-category.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Lecture Category !');
        }
        $lectureCategory = $this->query->find($id);
        $lectureCategory->delete();
        return response()->json(['status' => true, 'data' => $lectureCategory]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-category-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status  !');
        }
        $lectureCategory = $this->query->find($id);
        $status = $lectureCategory->status == 0 ? 1 : 0;
        $lectureCategory->update(['status' => $status]);
        if ($lectureCategory) {
            if ($lectureCategory->status == 1) {
                alert()->success('Lecture Category', 'Item Status Is Active');
            }
            if ($lectureCategory->status == 0) {
                alert()->success('Lecture Category', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Lecture Category', 'Failed To Update Status');

        }
        return redirect()->route('admin.lecture-category.index');
    }
    public function orderView():View
    {
        $lectureCategories =  LectureCategory::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('lectureCategories'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('lecture_categories')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
    }

}
