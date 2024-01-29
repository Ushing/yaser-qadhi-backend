<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JannahAndJahannamCategory;
 use App\Models\JannahAndJahannam;
use App\Query\JannahJahannamCategoryListQuery;
use App\Query\JannahAndJahannamQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class JannahandJahannamCategoryController extends Controller
{
    protected JannahJahannamCategoryListQuery $query;
    protected JannahAndJahannamQuery $JannahAndJahannamQuery;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.jannah-jahannam-category-list.';

    public function __construct(JannahJahannamCategoryListQuery $JannahJahannamCategoryListQuery, JannahAndJahannamQuery $JannahAndJahannamQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/jannah-jahannam-category-list';
        $this->query = $JannahJahannamCategoryListQuery;
        $this->JannahAndJahannamQuery = $JannahAndJahannamQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => ' Jannah and Jahannam Category List',
            'tableHeads' => ['Sr. No', 'Reference ID', 'Title', 'Jannah and Jahannam ID', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'reference_id', 'name' => 'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'jannah_and_jahannam_id', 'name' => 'jannah_and_jahannam_id'],
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
        $data = ['moduleName' => 'Jannah and Jahannam Category List Create',
        'arabicGrammar' => JannahAndJahannam::query()->get(),
       // 'arabicGrammar' => $this->JannahAndJahannamQuery->getActiveData()
               ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $rabicGrammarCategoryList = $this->query->saveRecitation($request);
        if ($rabicGrammarCategoryList) {
            alert()->success('Jannah and Jahannam Category List', 'Item Created Successfully');
            return redirect()->route('admin.jannah-jahannam-category-list.index');
        } else {
            alert()->error('Jannah and Jahannam Category List', 'Failed To Create');
            return redirect()->route('admin.jannah-jahannam-category-list.index');
        }
    }


    public function edit(int $id): View
    {
        $rabicGrammarCategoryList = $this->query->find($id);
        $data = [
            'moduleName' => 'Jannah and Jahannam Category List Edit',
            'arabicGrammar' => JannahAndJahannam::query()->get(),
            'arabicGrammarCategoryList' => $rabicGrammarCategoryList,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request,  $id): RedirectResponse
    {
        $rabicGrammarCategoryLists = JannahAndJahannamCategory::findOrFail($id);
        $updatetafsirnarratedDetail = $this->query->updateRecitation($request, $rabicGrammarCategoryLists);
        if ($updatetafsirnarratedDetail) {
            alert()->success('Jannah and Jahannam Category List', 'Item Updated Successfully');
            return redirect()->route('admin.jannah-jahannam-category-list.index');
        } else {
            alert()->error('Jannah and Jahannam Category List', 'Failed To Update');
            return redirect()->route('admin.jannah-jahannam-category-list.index');
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
                alert()->success('Jannah and Jahannam Category List', 'Item Status Is Active');
            }
            if ($arabicGrammerCategoryList->status == 0) {
                alert()->success('Jannah and Jahannam Category List', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Jannah and Jahannam Category List', 'Failed To Update Status');
        }
        return redirect()->route('admin.jannah-jahannam-category-list.index');
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
            if (!file_exists('uploads/jannahJahannamCategory/videos')) {
                mkdir('uploads/jannahJahannamCategory/videos', 0777, true);
            }
            $file->move('uploads/jannahJahannamCategory/videos', $fileName);
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
            if (!file_exists('uploads/jannahJahannamCategory/audio')) {
                mkdir('uploads/jannahJahannamCategory/audio', 0777, true);
            }
            $file->move('uploads/jannahJahannamCategory/audio', $fileName);
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
