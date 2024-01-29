<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akidah;
use App\Query\AkidahListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class AkidahController extends Controller
{
    protected AkidahListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.Akidah_list.';

    public function __construct(AkidahListQuery $AkidahListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/Akidah_list';
        $this->query = $AkidahListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Akidah List',
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
            'moduleName' => 'Akidah List Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $Akidahlist = $this->query->saveRecitation($request);
        if ($Akidahlist) {
            alert()->success('Akidah List', 'Item Created Successfully');
            return redirect()->route('admin.Akidah_list.index');
        } else {
            alert()->error('Akidah List', 'Failed To Create');
            return redirect()->route('admin.Akidah_list.index');
        }
    }
    public function show(int $id): View
    {
        $Akidahlist = $this->query->find($id);
        $data = [
            'moduleName' => 'Akidah List Details',
            'Akidahlist' => $Akidahlist,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $Akidahlist = $this->query->find($id);
        $data = [
            'moduleName' => 'Akidah List Edit',
            'Akidahlist' => $Akidahlist,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = Akidah::find($id);
        $Akidahlist = $this->query->updateRecitation($request, $content);
        if ($Akidahlist) {
            alert()->success('Akidah List', 'Item Updated Successfully');
            return redirect()->route('admin.Akidah_list.index');
        } else {
            alert()->error('Akidah List', 'Failed To Update');
            return redirect()->route('admin.Akidah_list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $Akidahlist = $this->query->find($id);

        $Akidahlist->delete();
        return response()->json(['status' => true, 'data' => $Akidahlist]);

    }

    public function statusChange($id): RedirectResponse
    {
        $Akidahlist = $this->query->find($id);
        $status = $$Akidahlist->status == 0 ? 1 : 0;
        $Akidahlist->update(['status' => $status]);
        if ($Akidahlist) {
            if ($Akidahlist->status == 1) {
                alert()->success('Akidah List Module', 'Item Status Is Active');
            }
            if ($Akidahlist->status == 0) {
                alert()->success('Akidah List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Akidah List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.Akidah_list.index');
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
            if (!file_exists('uploads/Akidahlist/videos')) {
                mkdir('uploads/Akidahlist/videos', 0777, true);
            }
            $file->move('uploads/Akidahlist/videos', $fileName);
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
            if (!file_exists('uploads/Akidahlist/audio')) {
                mkdir('uploads/Akidahlist/audio', 0777, true);
            }
            $file->move('uploads/Akidahlist/audio', $fileName);
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
