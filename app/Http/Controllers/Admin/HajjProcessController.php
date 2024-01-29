<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HajjProcess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Query\HajjProcessQuery;

use Illuminate\Http\Request;

class HajjProcessController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.hajj-processes.';

    public function __construct(HajjProcessQuery $hajjProcessQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/hajj_processes';
        $this->query = $hajjProcessQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Hajj Process',
            'tableHeads' => ['Sr. No', 'Process Number', 'Title', 'Description', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'process_no', 'name' => 'process_no'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'description', 'name' => 'description', 'orderable' => false],
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
        $data = ['moduleName' => 'Hajj Process Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $hajjCheckList = $this->query->saveHajjProcess($request);
        if ($hajjCheckList) {
            alert()->success('Hajj Process', 'Item Created Successfully');
            return redirect()->route('admin.hajj_processes.index');
        } else {
            alert()->error('Hajj Process', 'Failed To Create');
            return redirect()->route('admin.hajj_processes.index');
        }
    }

    public function edit(int $id): View
    {
        $hajjProcess = $this->query->find($id);
        $data = [
            'moduleName' => 'Hajj Process Edit',
            'hajjProcess' => $hajjProcess,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, HajjProcess $hajjProcess): RedirectResponse
    {
        $updateHajjProcess = $this->query->updateHajjProcess($request, $hajjProcess);
        if ($updateHajjProcess) {
            alert()->success('Hajj Process', 'Item Updated Successfully');
            return redirect()->route('admin.hajj_processes.index');
        } else {
            alert()->error('Hajj Process', 'Failed To Update');
            return redirect()->route('admin.hajj_processes.index');
        }
    }


    public function destroy($id): JsonResponse
    {
        $hajjProcess = $this->query->find($id);
        $hajjProcess->delete();
        return response()->json(['status' => true, 'data' => $hajjProcess]);
    }
}
