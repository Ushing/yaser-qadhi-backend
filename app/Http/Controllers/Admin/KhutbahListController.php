<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhutbahList;
use App\Query\KhutbahListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class KhutbahListController extends Controller
{
    protected KhutbahListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.khutbah_list.';

    public function __construct(KhutbahListQuery $KhutbahListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/khutbah_list';
        $this->query = $KhutbahListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'khutbah List',
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
            'moduleName' => 'khutbah List Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $khutbahList = $this->query->saveRecitation($request);
        if ($khutbahList) {
            alert()->success('khutbah List', 'Item Created Successfully');
            return redirect()->route('admin.khutbah_list.index');
        } else {
            alert()->error('khutbah List', 'Failed To Create');
            return redirect()->route('admin.khutbah_list.index');
        }
    }
    public function show(int $id): View
    {
        $khutbahList = $this->query->find($id);
        $data = [
            'moduleName' => 'khutbah List Details',
            'khutbahList' => $khutbahList,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $khutbahList = $this->query->find($id);
        $data = [
            'moduleName' => 'khutbah List Edit',
            'khutbahList' => $khutbahList,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = KhutbahList::find($id);
        $khutbahList = $this->query->updateRecitation($request, $content);
        if ($khutbahList) {
            alert()->success('khutbah List', 'Item Updated Successfully');
            return redirect()->route('admin.khutbah_list.index');
        } else {
            alert()->error('khutbah List', 'Failed To Update');
            return redirect()->route('admin.khutbah_list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $khutbahList = $this->query->find($id);
        if ($khutbahList->video) {
            unlink('uploads/khutbahList/videos' . $khutbahList->video);

        }
        // if ($khutbahList->audio) {
        //     unlink('uploads/khutbahList/audio' . $khutbahList->audio);

        // }
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
                alert()->success('khutbah List Module', 'Item Status Is Active');
            }
            if ($khutbahList->status == 0) {
                alert()->success('khutbah List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('khutbah List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.khutbah_list.index');
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
            if (!file_exists('uploads/khutbahList/videos')) {
                mkdir('uploads/khutbahList/videos', 0777, true);
            }
            $file->move('uploads/khutbahList/videos', $fileName);
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
            if (!file_exists('uploads/khutbahList/audio')) {
                mkdir('uploads/khutbahList/audio', 0777, true);
            }
            $file->move('uploads/khutbahList/audio', $fileName);
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
