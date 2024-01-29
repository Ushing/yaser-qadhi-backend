<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YasirLecture;
use App\Query\YasirQadhiQuery;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class YasirLectureController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.yasir-lecture.';

    public function __construct(YasirQadhiQuery $YasirQadhiQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/yasir-lecture';
        $this->query = $YasirQadhiQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Lecture Steps',
            'tableHeads' => ['Sr. No', 'Title', 'Icon Image', 'Cover Image', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'icon_image', 'name' => 'icon_image'],
                ['data' => 'cover_image', 'name' => 'cover_image'],
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
        $data = ['moduleName' => 'Lecture Step Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $yasirLecture = $this->query->saveYasirLecture($request);
        if ($yasirLecture) {
            alert()->success('Lecture Step', 'Item Created Successfully');
            return redirect()->route('admin.yasir-lecture.index');
        } else {
            alert()->error('Lecture Step', 'Failed To Create');
            return redirect()->route('admin.yasir-lecture.index');
        }
    }


    public function edit(int $id): View
    {
        $yasirLecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Step Edit',
            'yasirLecture' => $yasirLecture,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, YasirLecture $hajjPictorialStep): RedirectResponse
    {
        $yasirLecture = $this->query->updateYasirLecture($request, $hajjPictorialStep);
        if ($yasirLecture) {
            alert()->success('Lecture Step', 'Item Updated Successfully');
            return redirect()->route('admin.yasir-lecture.index');
        } else {
            alert()->error('Lecture Step', 'Failed To Update');
            return redirect()->route('admin.yasir-lecture.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $yasirLecture = $this->query->find($id);
        $yasirLecture->delete();
        return response()->json(['data' => $yasirLecture]);
    }
}
