<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReciteLanguage;
use App\Query\ReciteLanguageQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ReciteLanguageController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.recite-languages.';

    public function __construct(ReciteLanguageQuery $reciteLanguageQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/recite_languages';
        $this->query = $reciteLanguageQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Recite Language',
            'tableHeads' => ['Sr. No', 'Name', 'Status', 'Status Change', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title'],
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
        $data = ['moduleName' => 'Recite Language Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $reciteLanguage = $this->query->saveReciteLanguage($request);
        if ($reciteLanguage) {
            alert()->success('Recite Language', 'Item Created Successfully');
            return redirect()->route('admin.recite_languages.index');
        } else {
            alert()->error('Recite Language', 'Failed To Create');
            return redirect()->route('admin.recite_languages.index');
        }
    }

    public function show(int $id)
    {

    }

    public function edit(int $id): View
    {
        $reciteLanguage = $this->query->find($id);
        $data = [
            'moduleName' => 'Recite Language Edit',
            'reciteLanguage' => $reciteLanguage,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, ReciteLanguage $reciteLanguage): RedirectResponse
    {
        $updateReciteLanguage = $this->query->updateReciteLanguage($request, $reciteLanguage);
        if ($updateReciteLanguage) {
            alert()->success('Recite Language', 'Item Updated Successfully');
            return redirect()->route('admin.recite_languages.index');
        } else {
            alert()->error('ReciteLanguage', 'Failed To Update');
            return redirect()->route('admin.recite_languages.index');
        }
    }

    public function statusChange($id): RedirectResponse
    {
        $reciteLanguage = $this->query->find($id);
        $status = $reciteLanguage->status == 0 ? 1 : 0;
        $reciteLanguage->update(['status' => $status]);
        if ($reciteLanguage) {
            if ($reciteLanguage->status == 1) {
                alert()->success('Recite Language', 'Item Status Is Active');
            }
            if ($reciteLanguage->status == 0) {
                alert()->success('Recite Language', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Recite Language', 'Failed To Update Status');

        }
        return redirect()->route('admin.recite_languages.index');
    }



}
