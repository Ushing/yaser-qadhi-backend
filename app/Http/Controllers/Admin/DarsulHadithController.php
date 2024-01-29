<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DarsulHadith;
use App\Query\DarsulHadithQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;
class DarsulHadithController extends Controller
{
    protected DarsulHadithQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.DarsulHadith_list.';

    public function __construct(DarsulHadithQuery $DarsulHadithQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/DarsulHadith_list';
        $this->query = $DarsulHadithQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Darsul Hadith List',
            'tableHeads' => ['Sr. No', 'Reference ID', 'Title', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'reference_id', 'name' => 'reference_id'],
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
        $data = [
            'moduleName' => 'Darsul Hadith List Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $khutbahList = $this->query->saveRecitation($request);
        if ($khutbahList) {
            alert()->success('Darsul Hadith List', 'Item Created Successfully');
            return redirect()->route('admin.DarsulHadith_list.index');
        } else {
            alert()->error('Darsul Hadith List', 'Failed To Create');
            return redirect()->route('admin.DarsulHadith_list.index');
        }
    }
    public function show(int $id): View
    {
        $khutbahList = $this->query->find($id);
        $data = [
            'moduleName' => 'Darsul Hadith List Details',
            'DarsulHadithlist' => $khutbahList,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $khutbahList = $this->query->find($id);
        $data = [
            'moduleName' => 'Darsul Hadith List Edit',
            'DarsulHadithlist' => $khutbahList,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = DarsulHadith::find($id);
        $khutbahList = $this->query->updateRecitation($request, $content);
        if ($khutbahList) {
            alert()->success('Darsul Hadith List', 'Item Updated Successfully');
            return redirect()->route('admin.DarsulHadith_list.index');
        } else {
            alert()->error('Darsul Hadith List', 'Failed To Update');
            return redirect()->route('admin.DarsulHadith_list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $khutbahList = $this->query->find($id);

        $khutbahList->delete();
        return response()->json(['status' => true, 'data' => $khutbahList]);

    }

    public function statusChange($id): RedirectResponse
    {
        $khutbahList = $this->query->find($id);
        $status = $khutbahList->status == 0 ? 1 : 0;
        $khutbahList->update(['status' => $status]);
        if ($khutbahList) {
            if ($khutbahList->status == 1) {
                alert()->success('Darsul Hadith List Module', 'Item Status Is Active');
            }
            if ($khutbahList->status == 0) {
                alert()->success('Darsul Hadith List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Darsul Hadith List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.DarsulHadith_list.index');
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
            if (!file_exists('uploads/DarsulHadithList/videos')) {
                mkdir('uploads/DarsulHadithList/videos', 0777, true);
            }
            $file->move('uploads/DarsulHadithList/videos', $fileName);
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
            if (!file_exists('uploads/DarsulHadithList/audio')) {
                mkdir('uploads/DarsulHadithList/audio', 0777, true);
            }
            $file->move('uploads/DarsulHadithList/audio', $fileName);
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
