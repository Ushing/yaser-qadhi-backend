<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stories;
use App\Query\StoriesQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class StoriesController extends Controller
{
    protected StoriesQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.stories.';

    public function __construct(StoriesQuery $StoriesQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/stories';
        $this->query = $StoriesQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Stories',
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
            'moduleName' => 'Stories Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $Storie = $this->query->saveRecitation($request);
        if ($Storie) {
            alert()->success('Stories', 'Item Created Successfully');
            return redirect()->route('admin.stories.index');
        } else {
            alert()->error('Stories', 'Failed To Create');
            return redirect()->route('admin.stories.index');
        }
    }
    public function show(int $id): View
    {
        $Storie = $this->query->find($id);
        $data = [
            'moduleName' => 'Stories Details',
            'stories' => $Storie,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $Storie = $this->query->find($id);
        $data = [
            'moduleName' => 'Stories Edit',
            'stories' => $Storie,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = Stories::find($id);
        $Storie = $this->query->updateRecitation($request, $content);
        if ($Storie) {
            alert()->success('Stories', 'Item Updated Successfully');
            return redirect()->route('admin.stories.index');
        } else {
            alert()->error('Stories', 'Failed To Update');
            return redirect()->route('admin.stories.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $Storie = $this->query->find($id);

        $Storie->delete();
        return response()->json(['status' => true, 'data' => $Storie]);

    }

    public function statusChange($id): RedirectResponse
    {
        $Storie = $this->query->find($id);
        $status = $$Storie->status == 0 ? 1 : 0;
        $Storie->update(['status' => $status]);
        if ($Storie) {
            if ($Storie->status == 1) {
                alert()->success('Stories Module', 'Item Status Is Active');
            }
            if ($Storie->status == 0) {
                alert()->success('Stories Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Stories Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.stories.index');
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
            if (!file_exists('uploads/Storie/videos')) {
                mkdir('uploads/Storie/videos', 0777, true);
            }
            $file->move('uploads/Storie/videos', $fileName);
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
            if (!file_exists('uploads/Storie/audio')) {
                mkdir('uploads/Storie/audio', 0777, true);
            }
            $file->move('uploads/Storie/audio', $fileName);
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
