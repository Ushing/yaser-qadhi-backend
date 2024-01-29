<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArabicGrammar;
use App\Query\ArabicGrammarQuery;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ArabicGrammarController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.arabic-grammar.';

    public function __construct(ArabicGrammarQuery $ArabicGrammarQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/arabic-grammar';
        $this->query = $ArabicGrammarQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Arabic Grammar Steps',
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
        $data = ['moduleName' => 'Arabic Grammar Step Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $arabicGrammar = $this->query->saveArabicGrammar($request);
        if ($arabicGrammar) {
            alert()->success('Arabic Grammar Step', 'Item Created Successfully');
            return redirect()->route('admin.arabic-grammar.index');
        } else {
            alert()->error('Arabic Grammar Step', 'Failed To Create');
            return redirect()->route('admin.arabic-grammar.index');
        }
    }


    public function edit(int $id): View
    {
        $arabicGrammar = $this->query->find($id);
        $data = [
            'moduleName' => 'Arabic Grammar Step Edit',
            'arabicGrammar' => $arabicGrammar,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, ArabicGrammar $hajjPictorialStep): RedirectResponse
    {
        $updatearabicGrammar = $this->query->updateArabicGrammar($request, $hajjPictorialStep);
        if ($updatearabicGrammar) {
            alert()->success('Arabic Grammar Step', 'Item Updated Successfully');
            return redirect()->route('admin.arabic-grammar.index');
        } else {
            alert()->error('Arabic Grammar Step', 'Failed To Update');
            return redirect()->route('admin.arabic-grammar.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $arabicGrammar = $this->query->find($id);
        $arabicGrammar->delete();
        return response()->json(['data' => $arabicGrammar]);
    }
}
