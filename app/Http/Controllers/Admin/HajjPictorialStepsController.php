<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HajjPictorialSteps;
use App\Query\HajjPictorialStepsQuery;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class HajjPictorialStepsController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.hajj-pictorial-steps.';

    public function __construct(HajjPictorialStepsQuery $hajjPictorialStepsQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/hajj_pictorial_steps';
        $this->query = $hajjPictorialStepsQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Hajj Pictorial Steps',
            'tableHeads' => ['Sr. No', 'Step No', 'Title', 'Image', 'Video', 'Description', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'step_no', 'name' => 'step_no'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'image', 'name' => 'image'],
                ['data' => 'video', 'name' => 'video'],
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
        $data = ['moduleName' => 'Hajj Pictorial Step Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $hajjPictorialSteps = $this->query->saveHajjPictorialSteps($request);
        if ($hajjPictorialSteps) {
            alert()->success('Hajj Pictorial Step', 'Item Created Successfully');
            return redirect()->route('admin.hajj_pictorial_steps.index');
        } else {
            alert()->error('Hajj Pictorial Step', 'Failed To Create');
            return redirect()->route('admin.hajj_pictorial_steps.index');
        }
    }


    public function edit(int $id): View
    {
        $hajjPictorialSteps = $this->query->find($id);
        $data = [
            'moduleName' => 'Hajj Pictorial Step Edit',
            'hajjPictorialSteps' => $hajjPictorialSteps,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, HajjPictorialSteps $hajjPictorialStep): RedirectResponse
    {
        $updateHajjPictorialSteps = $this->query->updateHajjPictorialStep($request, $hajjPictorialStep);
        if ($updateHajjPictorialSteps) {
            alert()->success('Hajj Pictorial Step', 'Item Updated Successfully');
            return redirect()->route('admin.hajj_pictorial_steps.index');
        } else {
            alert()->error('Hajj Pictorial Step', 'Failed To Update');
            return redirect()->route('admin.hajj_pictorial_steps.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $hajjPictorialSteps = $this->query->find($id);
        $hajjPictorialSteps->delete();
        return response()->json(['status' => true, 'data' => $hajjPictorialSteps]);
    }
}
