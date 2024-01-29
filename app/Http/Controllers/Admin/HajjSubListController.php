<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HajjSubRequest;
use App\Models\HajjChecklist;
use App\Models\HajjSubList;
use App\Query\HajjSubListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HajjSubListController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.hajj-sub-lists.';

    public function __construct(HajjSubListQuery $hajjSubListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/hajj_sub_lists';
        $this->query = $hajjSubListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Hajj Sub List',
            'tableHeads' => ['Sr. No', 'Title','Hajj Check Title', 'Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'checklist_id', 'name' => 'checklist_id'],
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
        $data = [
            'moduleName' => 'Hajj Sub List Create',
            'hajjCheckLists' => HajjChecklist::query()->where('status','true')->get()

            ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(HajjSubRequest $request): RedirectResponse
    {
        $hajjSubList = $this->query->saveHajjSubList($request);
        if ($hajjSubList) {
            alert()->success('Hajj Sub List', 'Item Created Successfully');
            return redirect()->route('admin.hajj_sub_lists.index');
        } else {
            alert()->error('Hajj Sub List', 'Failed To Create');
            return redirect()->route('admin.hajj_sub_lists.index');
        }
    }


    public function edit(int $id): View
    {
        $hajjSubList = $this->query->find($id);
        $data = [
            'moduleName' => 'Hajj Sub List Edit',
            'hajjSubList' => $hajjSubList,
            'hajjCheckLists' => HajjChecklist::query()->where('status','true')->get()
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(HajjSubRequest $request, HajjSubList $hajjSubList): RedirectResponse
    {
        $updateHajjSubList = $this->query->updateHajjSubList($request, $hajjSubList);
        if ($updateHajjSubList) {
            alert()->success('Hajj Sub List', 'Item Updated Successfully');
            return redirect()->route('admin.hajj_sub_lists.index');
        } else {
            alert()->error('Hajj Sub List', 'Failed To Update');
            return redirect()->route('admin.hajj_sub_lists.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $hajjSubList = $this->query->find($id);
        $hajjSubList->delete();
        return response()->json(['status' => true, 'data' => $hajjSubList]);
    }


}
