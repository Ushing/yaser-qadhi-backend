<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JannahAndJahannam;
use App\Query\JannahAndJahannamQuery;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class JannahandJahannamController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.jannah-jahannam.';

    public function __construct(JannahAndJahannamQuery $JannahAndJahannamQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/jannah-jahannam';
        $this->query = $JannahAndJahannamQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Jannah and Jahannam Steps',
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
        $data = ['moduleName' => 'Jannah and Jahannam Step Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $JannahandJahannam = $this->query->saveJannahandJahannam($request);
        if ($JannahandJahannam) {
            alert()->success('Jannah and Jahannam Step', 'Item Created Successfully');
            return redirect()->route('admin.jannah-jahannam.index');
        } else {
            alert()->error('Jannah and Jahannam Step', 'Failed To Create');
            return redirect()->route('admin.jannah-jahannam.index');
        }
    }


    public function edit(int $id): View
    {
        $JannahandJahannam = $this->query->find($id);
        $data = [
            'moduleName' => 'Jannah and Jahannam Step Edit',
            'Jannah' => $JannahandJahannam,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, JannahAndJahannam $JannahandJahannam): RedirectResponse
    {
        $updateJannahandJahannam = $this->query->updateJannahandJahannam($request, $JannahandJahannam);
        if ($updateJannahandJahannam) {
            alert()->success('Jannah and Jahannam Step', 'Item Updated Successfully');
            return redirect()->route('admin.jannah-jahannam.index');
        } else {
            alert()->error('Jannah and Jahannam Step', 'Failed To Update');
            return redirect()->route('admin.jannah-jahannam.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $JannahandJahannam = $this->query->find($id);
        $JannahandJahannam->delete();
        return response()->json(['data' => $JannahandJahannam]);
    }
}
