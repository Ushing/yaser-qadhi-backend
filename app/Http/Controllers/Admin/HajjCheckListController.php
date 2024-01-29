<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HajjCheckList;
use App\Query\HajjCheckListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HajjCheckListController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.hajj-check-lists.';

    public function __construct(HajjCheckListQuery $hajjCheckListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/hajj_check_lists';
        $this->query = $hajjCheckListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Hajj Check List',
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
        $data = ['moduleName' => 'Hajj Check List Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $hajjCheckList = $this->query->saveHajjCheckList($request);
        if ($hajjCheckList) {
            alert()->success('Hajj Check List', 'Item Created Successfully');
            return redirect()->route('admin.hajj_check_lists.index');
        } else {
            alert()->error('Hajj Check List', 'Failed To Create');
            return redirect()->route('admin.hajj_check_lists.index');
        }
    }


    public function edit(int $id): View
    {
        $hajjCheckList = $this->query->find($id);
        $data = [
            'moduleName' => 'Hajj Check List Edit',
            'hajjCheckList' => $hajjCheckList,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, HajjCheckList $hajjCheckList): RedirectResponse
    {
        $updateHajjCheckList = $this->query->updateHajjCheckList($request, $hajjCheckList);
        if ($updateHajjCheckList) {
            alert()->success('Hajj Check List', 'Item Updated Successfully');
            return redirect()->route('admin.hajj_check_lists.index');
        } else {
            alert()->error('Hajj Check List', 'Failed To Update');
            return redirect()->route('admin.hajj_check_lists.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $hajjCheckList = $this->query->find($id);
        $hajjCheckList->delete();
        return response()->json(['status' => true, 'data' => $hajjCheckList]);
    }


}
