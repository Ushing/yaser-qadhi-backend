<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\TagDetail;
use App\Query\TagQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TagController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.tags.';

    public function __construct(TagQuery $tagQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/tag';
        $this->query = $tagQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Tag',
            'tableHeads' => ['Sr. No', 'Name', 'Type', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'type', 'name' => 'type'],
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
        $data = ['moduleName' => 'Tag Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $tag = $this->query->saveTag($request);
        if ($tag) {
            alert()->success('Tag', 'Item Created Successfully');
            return redirect()->route('admin.tag.index');
        } else {
            alert()->error('Tag', 'Failed To Create');
            return redirect()->route('admin.tag.index');
        }
    }

    public function show(int $id): View
    {
        $tag = $this->query->find($id);
        $data = [
            'moduleName' => 'Tag Details',
            'tag' => $tag,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        $tag = $this->query->find($id);
        $data = [
            'moduleName' => 'Tag Edit',
            'tag' => $tag,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $updateTag = $this->query->updateTag($request, $tag);
        if ($updateTag) {
            alert()->success('Tag', 'Item Updated Successfully');
            return redirect()->route('admin.tag.index');
        } else {
            alert()->error('Tag', 'Failed To Update');
            return redirect()->route('admin.tag.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $tag = $this->query->find($id);
        $tag->delete();
        return response()->json(['status' => true, 'data' => $tag]);
    }


}
