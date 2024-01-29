<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageofQuran;
use App\Query\MessageofQuranQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class MessageOfQuransController extends Controller
{
    protected MessageofQuranQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.message_of_qurans.';

    public function __construct(MessageofQuranQuery $MessageofQuranQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/message_of_qurans';
        $this->query = $MessageofQuranQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Message of Quran',
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
            'moduleName' => 'Message of Quran Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $messageOfQurans = $this->query->saveRecitation($request);
        if ($messageOfQurans) {
            alert()->success('Message of Quran', 'Item Created Successfully');
            return redirect()->route('admin.message_of_qurans.index');
        } else {
            alert()->error('Message of Quran', 'Failed To Create');
            return redirect()->route('admin.message_of_qurans.index');
        }
    }
    public function show(int $id): View
    {
        $messageOfQurans = $this->query->find($id);
        $data = [
            'moduleName' => 'Message of Quran Details',
            'message_of_qurans' => $messageOfQurans,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $messageOfQurans = $this->query->find($id);
        $data = [
            'moduleName' => 'Message of Quran Edit',
            'message_of_qurans' => $messageOfQurans,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = MessageofQuran::find($id);
        $messageOfQurans = $this->query->updateRecitation($request, $content);
        if ($messageOfQurans) {
            alert()->success('Message of Quran', 'Item Updated Successfully');
            return redirect()->route('admin.message_of_qurans.index');
        } else {
            alert()->error('Message of Quran', 'Failed To Update');
            return redirect()->route('admin.message_of_qurans.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $messageOfQurans = $this->query->find($id);

        $messageOfQurans->delete();
        return response()->json(['status' => true, 'data' => $messageOfQurans]);

    }

    public function statusChange($id): RedirectResponse
    {
        $messageOfQurans = $this->query->find($id);
        $status = $$messageOfQurans->status == 0 ? 1 : 0;
        $messageOfQurans->update(['status' => $status]);
        if ($messageOfQurans) {
            if ($messageOfQurans->status == 1) {
                alert()->success('Message of Quran Module', 'Item Status Is Active');
            }
            if ($messageOfQurans->status == 0) {
                alert()->success('Message of Quran Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Message of Quran Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.message_of_qurans.index');
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
            if (!file_exists('uploads/messageOfQurans/videos')) {
                mkdir('uploads/messageOfQurans/videos', 0777, true);
            }
            $file->move('uploads/messageOfQurans/videos', $fileName);
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
            if (!file_exists('uploads/messageOfQurans/audio')) {
                mkdir('uploads/messageOfQurans/audio', 0777, true);
            }
            $file->move('uploads/messageOfQurans/audio', $fileName);
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
