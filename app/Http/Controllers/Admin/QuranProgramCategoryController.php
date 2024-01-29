<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranProgramCategory;
use App\Query\QuranProgramCategoryQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranProgramCategoryController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-program-categories.';

    public function __construct(QuranProgramCategoryQuery $quranProgramCategoryQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran_program_category';
        $this->query = $quranProgramCategoryQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran Program Category',
            'tableHeads' => ['Sr. No', 'Title', 'Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'status', 'name' => 'status'],
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
        $data = ['moduleName' => 'Quran Program Category Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $quranProgramCategory = $this->query->saveQuranProgramCategory($request);
        if ($quranProgramCategory) {
            alert()->success('Quran Program Category', 'Item Created Successfully');
            return redirect()->route('admin.quran_program_category.index');
        } else {
            alert()->error('Quran Program Category', 'Failed To Create');
            return redirect()->route('admin.quran_program_category.index');
        }
    }


    public function edit(int $id): View
    {
        $quranProgramCategory = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Program Category Edit',
            'quranProgramCategory' => $quranProgramCategory,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, QuranProgramCategory $quranProgramCategory): RedirectResponse
    {
        $quranProgramCategory = $this->query->updateQuranProgramCategory($request, $quranProgramCategory);
        if ($quranProgramCategory) {
            alert()->success('Quran Program Category', 'Item Updated Successfully');
            return redirect()->route('admin.quran_program_category.index');
        } else {
            alert()->error('Quran Program Category', 'Failed To Update');
            return redirect()->route('admin.quran_program_category.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranProgramCategory = $this->query->find($id);
        $quranProgramCategory->delete();
        return response()->json(['status' => true, 'data' => $quranProgramCategory]);
    }


}
