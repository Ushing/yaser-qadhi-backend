<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArabicGrammerCategoryList;
use App\Models\ArabicGrammar;
use App\Query\ArabicGrammerCategoryListQuery;
use App\Query\ArabicGrammarQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class ArabicGrammerCategoryListController extends Controller
{
    protected ArabicGrammerCategoryListQuery $query;
    protected ArabicGrammarQuery $ArabicGrammarQuery;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.arabic-grammer-category-list.';

    public function __construct(ArabicGrammerCategoryListQuery $ArabicGrammerCategoryListQuery, ArabicGrammarQuery $ArabicGrammarQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/arabic-grammer-category-list';
        $this->query = $ArabicGrammerCategoryListQuery;
        $this->ArabicGrammarQuery = $ArabicGrammarQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => ' Arabic Grammar Category List',
            'tableHeads' => ['Sr. No', 'Reference ID', 'Title', 'Arabic Grammar ID', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'reference_id', 'name' => 'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'arabic_grammar_id', 'name' => 'arabic_grammar_id'],
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
        $data = ['moduleName' => 'Arabic Grammar Category List Create',
        'arabicGrammar' => ArabicGrammar::query()->get(),
       // 'arabicGrammar' => $this->ArabicGrammarQuery->getActiveData()
               ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $rabicGrammarCategoryList = $this->query->saveRecitation($request);
        if ($rabicGrammarCategoryList) {
            alert()->success('Arabic Grammar Category List', 'Item Created Successfully');
            return redirect()->route('admin.arabic-grammer-category-list.index');
        } else {
            alert()->error('Arabic Grammar Category List', 'Failed To Create');
            return redirect()->route('admin.arabic-grammer-category-list.index');
        }
    }


    public function edit(int $id): View
    {
        $rabicGrammarCategoryList = $this->query->find($id);
        $data = [
            'moduleName' => 'Arabic Grammar Category List Edit',
            'arabicGrammar' => ArabicGrammar::query()->get(),
            'arabicGrammarCategoryList' => $rabicGrammarCategoryList,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request,  $id): RedirectResponse
    {
        $rabicGrammarCategoryLists = ArabicGrammerCategoryList::findOrFail($id);
        $updatetafsirnarratedDetail = $this->query->updateRecitation($request, $rabicGrammarCategoryLists);
        if ($updatetafsirnarratedDetail) {
            alert()->success('Arabic Grammar Category List', 'Item Updated Successfully');
            return redirect()->route('admin.arabic-grammer-category-list.index');
        } else {
            alert()->error('Arabic Grammar Category List', 'Failed To Update');
            return redirect()->route('admin.arabic-grammer-category-list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $rabicGrammarCategoryList = $this->query->find($id);
        $rabicGrammarCategoryList->delete();
        return response()->json(['status' => true, 'data' => $rabicGrammarCategoryList]);
    }

    public function statusChange($id): RedirectResponse
    {
        $arabicGrammerCategoryList = $this->query->find($id);
        $status = $$arabicGrammerCategoryList->status == 0 ? 1 : 0;
        $arabicGrammerCategoryList->update(['status' => $status]);
        if ($arabicGrammerCategoryList) {
            if ($arabicGrammerCategoryList->status == 1) {
                alert()->success('Arabic Grammar Category List', 'Item Status Is Active');
            }
            if ($arabicGrammerCategoryList->status == 0) {
                alert()->success('Arabic Grammar Category List', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Arabic Grammar Category List', 'Failed To Update Status');
        }
        return redirect()->route('admin.arabic-grammer-category-list.index');
    }


    public function uploadRecitationVideo(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            if (!file_exists('uploads/arabicGrammerCategoryList/videos')) {
                mkdir('uploads/arabicGrammerCategoryList/videos', 0777, true);
            }
            $file->move('uploads/arabicGrammerCategoryList/videos', $fileName);
            return [
                'filename' => $fileName
            ];
        }
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    public function uploadRecitationAudio(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            if (!file_exists('uploads/arabicGrammerCategoryList/audio')) {
                mkdir('uploads/arabicGrammerCategoryList/audio', 0777, true);
            }
            $file->move('uploads/arabicGrammerCategoryList/audio', $fileName);
            return [
                'filename' => $fileName
            ];
        }
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
}
