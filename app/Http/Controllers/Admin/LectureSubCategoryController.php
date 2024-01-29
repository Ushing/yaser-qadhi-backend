<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureSubCategoryRequest;
use App\Models\LectureCategory;
use App\Models\LectureSubCategory;
use App\Query\LectureCategoryQuery;
use App\Query\LectureSubCategoryQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LectureSubCategoryController extends Controller
{

    protected $query;
    protected $lectureCategoryQuery;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.lecture-sub-category.';

    public function __construct(LectureSubCategoryQuery $lectureSubCategoryQuery, LectureCategoryQuery $lectureCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/lecture-sub-category';
        $this->query = $lectureSubCategoryQuery;
        $this->lectureCategoryQuery = $lectureCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Lecture Sub Category !');
        }
        $data = [
            'moduleName' => 'Lecture Sub Category',
            'tableHeads' => ['Sr. No', 'Name', 'Lecture Category', 'Position', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'lecture_category_id', 'name' => 'lecture_category_id'],
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
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Lecture Sub Category !');
        }
        $data = [
            'moduleName' => 'Lecture Sub Category Create',
            'lectureCategories' => $this->lectureCategoryQuery->getActiveData()
        ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(LectureSubCategoryRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Lecture Sub Category !');
        }
        $lectureSubCategory = $this->query->saveLectureSubCategory($request);
        if ($lectureSubCategory) {
            alert()->success('Lecture Sub Category', 'Item Created Successfully');
            return redirect()->route('admin.lecture-sub-category.index');
        } else {
            alert()->error('Lecture Sub Category', 'Failed To Create');
            return redirect()->route('admin.lecture-sub-category.index');
        }
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture Sub Category !');
        }
        $lectureSubCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Sub Category Details',
            'lectureSubCategory' => $lectureSubCategory,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture Sub Category !');
        }
        $lectureSubCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Sub Category Edit',
            'lectureCategories' => $this->lectureCategoryQuery->getActiveData(),
            'lectureSubCategory' => $lectureSubCategory,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(LectureSubCategoryRequest $request, LectureSubCategory $lectureSubCategory): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Lecture Sub Category !');
        }
        $updateLectureSubCategory = $this->query->updateLectureSubCategory($request, $lectureSubCategory);
        if ($updateLectureSubCategory) {
            alert()->success('Lecture Sub Category', 'Item Updated Successfully');
            return redirect()->route('admin.lecture-sub-category.index');
        } else {
            alert()->error('Lecture Sub Category', 'Failed To Update');
            return redirect()->route('admin.lecture-sub-category.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Lecture Sub Category !');
        }
        $lectureSubCategory = $this->query->find($id);
        $lectureSubCategory->delete();
        return response()->json(['status' => true, 'data' => $lectureSubCategory]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-sub-category-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $lectureSubCategory = $this->query->find($id);
        $status = $lectureSubCategory->status == 0 ? 1 : 0;
        $lectureSubCategory->update(['status' => $status]);
        if ($lectureSubCategory) {
            if ($lectureSubCategory->status == 1) {
                alert()->success('Lecture Sub Category', 'Item Status Is Active');
            }
            if ($lectureSubCategory->status == 0) {
                alert()->success('Lecture Sub Category', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Lecture Sub Category', 'Failed To Update Status');
        }
        return redirect()->route('admin.lecture-sub-category.index');
    }

    //    Fetch all lecture subcategory by lecture category id
    public function getLectureSubCategoryByLectureCategoryId(Request $request): JsonResponse
    {
        $lectureSubCategories = [];
        if (!empty($request->lectureCategoryId)) {
            $lectureSubCategories = $this->query->getAllLectureSubCategoryOfLectureCategoryId($request->lectureCategoryId);
        }
        return response()->json(['status' => true, 'lectureSubCategories' => $lectureSubCategories]);
    }

    public function orderView():View
    {
        $lectureSubCategories =  LectureSubCategory::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('lectureSubCategories'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('lecture_sub_categories')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
    }


}
