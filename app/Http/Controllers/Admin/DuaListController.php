<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuaList;
use App\Query\DuaListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class DuaListController extends Controller
{
    protected DuaListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.dua_list.';

    public function __construct(DuaListQuery $DuaListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/dua_list';
        $this->query = $DuaListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Dua List',
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
            'moduleName' => 'Dua List Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $Dualist = $this->query->saveRecitation($request);
        if ($Dualist) {
            alert()->success('Dua List', 'Item Created Successfully');
            return redirect()->route('admin.dua_list.index');
        } else {
            alert()->error('Dua List', 'Failed To Create');
            return redirect()->route('admin.dua_list.index');
        }
    }
    public function show(int $id): View
    {
        $Dualist = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua List Details',
            'dua_list' => $Dualist,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $Dualist = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua List Edit',
            'dua_list' => $Dualist,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = DuaList::find($id);
        $Dualist = $this->query->updateRecitation($request, $content);
        if ($Dualist) {
            alert()->success('Dua List', 'Item Updated Successfully');
            return redirect()->route('admin.dua_list.index');
        } else {
            alert()->error('Dua List', 'Failed To Update');
            return redirect()->route('admin.dua_list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $Dualist = $this->query->find($id);

        $Dualist->delete();
        return response()->json(['status' => true, 'data' => $Dualist]);

    }

    public function statusChange($id): RedirectResponse
    {
        $Dualist = $this->query->find($id);
        $status = $$Dualist->status == 0 ? 1 : 0;
        $Dualist->update(['status' => $status]);
        if ($Dualist) {
            if ($Dualist->status == 1) {
                alert()->success('Dua List Module', 'Item Status Is Active');
            }
            if ($Dualist->status == 0) {
                alert()->success('Dua List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Dua List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.dua_list.index');
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
            if (!file_exists('uploads/dua_list/videos')) {
                mkdir('uploads/dua_list/videos', 0777, true);
            }
            $file->move('uploads/dua_list/videos', $fileName);
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
            if (!file_exists('uploads/dua_list/audio')) {
                mkdir('uploads/dua_list/audio', 0777, true);
            }
            $file->move('uploads/dua_list/audio', $fileName);
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
