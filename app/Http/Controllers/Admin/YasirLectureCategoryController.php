<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YasirLectureCategory;
use App\Models\YasirLecture;
use App\Query\YasirLectureCategoryListQuery;
use App\Query\YasirQadhiQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class YasirLectureCategoryController extends Controller
{
    protected YasirLectureCategoryListQuery $query;
    protected YasirQadhiQuery $YasirQadhiQuery;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.yasir-lecture-category-list.';

    public function __construct(YasirLectureCategoryListQuery $YasirLectureCategoryListQuery, YasirQadhiQuery $YasirQadhiQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/yasir-lecture-category-list';
        $this->query = $YasirLectureCategoryListQuery;
        $this->YasirQadhiQuery = $YasirQadhiQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => ' Lecture Category List',
            'tableHeads' => ['Sr. No', 'Reference ID', 'Title', 'Yasir Lecture ID', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'reference_id', 'name' => 'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'yasir_lecture_id', 'name' => 'yasir_lecture_id'],
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
        $data = ['moduleName' => 'Lecture Category List Create',
        'arabicGrammar' => YasirLecture::query()->get(),
       // 'arabicGrammar' => $this->YasirQadhiQuery->getActiveData()
               ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $rabicGrammarCategoryList = $this->query->saveRecitation($request);
        if ($rabicGrammarCategoryList) {
            alert()->success('Lecture Category List', 'Item Created Successfully');
            return redirect()->route('admin.yasir-lecture-category-list.index');
        } else {
            alert()->error('Lecture Category List', 'Failed To Create');
            return redirect()->route('admin.yasir-lecture-category-list.index');
        }
    }


    public function edit(int $id): View
    {
        $rabicGrammarCategoryList = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Category List Edit',
            'arabicGrammar' => YasirLecture::query()->get(),
            'arabicGrammarCategoryList' => $rabicGrammarCategoryList,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request,  $id): RedirectResponse
    {
        $rabicGrammarCategoryLists = YasirLectureCategory::findOrFail($id);
        $updatetafsirnarratedDetail = $this->query->updateRecitation($request, $rabicGrammarCategoryLists);
        if ($updatetafsirnarratedDetail) {
            alert()->success('Lecture Category List', 'Item Updated Successfully');
            return redirect()->route('admin.yasir-lecture-category-list.index');
        } else {
            alert()->error('Lecture Category List', 'Failed To Update');
            return redirect()->route('admin.yasir-lecture-category-list.index');
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
                alert()->success('Lecture Category List', 'Item Status Is Active');
            }
            if ($arabicGrammerCategoryList->status == 0) {
                alert()->success('Lecture Category List', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Lecture Category List', 'Failed To Update Status');
        }
        return redirect()->route('admin.yasir-lecture-category-list.index');
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
            if (!file_exists('uploads/Yasir_Qadhi/videos')) {
                mkdir('uploads/Yasir_Qadhi/videos', 0777, true);
            }
            $file->move('uploads/Yasir_Qadhi/videos', $fileName);
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
            if (!file_exists('uploads/Yasir_Qadhi/audio')) {
                mkdir('uploads/Yasir_Qadhi/audio', 0777, true);
            }
            $file->move('uploads/Yasir_Qadhi/audio', $fileName);
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
