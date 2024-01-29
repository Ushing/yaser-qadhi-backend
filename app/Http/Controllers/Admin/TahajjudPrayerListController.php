<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahajjudPrayerList;
use App\Query\TahajjudPrayerListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class TahajjudPrayerListController extends Controller
{
    protected TahajjudPrayerListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.tahajjud_prayer.';

    public function __construct(TahajjudPrayerListQuery $TahajjudPrayerListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/tahajjud_prayer';
        $this->query = $TahajjudPrayerListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Tahajjud  Prayer',
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
            'moduleName' => 'Tahajjud  Prayer Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $tahajjud_prayer = $this->query->saveRecitation($request);
        if ($tahajjud_prayer) {
            alert()->success('Tahajjud  Prayer', 'Item Created Successfully');
            return redirect()->route('admin.tahajjud_prayer.index');
        } else {
            alert()->error('Tahajjud  Prayer', 'Failed To Create');
            return redirect()->route('admin.tahajjud_prayer.index');
        }
    }
    public function show(int $id): View
    {
        $tahajjud_prayer = $this->query->find($id);
        $data = [
            'moduleName' => 'Tahajjud  Prayer Details',
            'tahajjud_prayers' => $tahajjud_prayer,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $tahajjud_prayer = $this->query->find($id);
        $data = [
            'moduleName' => 'Tahajjud  Prayer Edit',
            'tahajjud_prayers' => $tahajjud_prayer,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = TahajjudPrayerList::find($id);
        $tahajjud_prayer = $this->query->updateRecitation($request, $content);
        if ($tahajjud_prayer) {
            alert()->success('Tahajjud  Prayer', 'Item Updated Successfully');
            return redirect()->route('admin.tahajjud_prayer.index');
        } else {
            alert()->error('Tahajjud  Prayer', 'Failed To Update');
            return redirect()->route('admin.tahajjud_prayer.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $tahajjud_prayer = $this->query->find($id);
        if ($tahajjud_prayer->video) {
            unlink('uploads/tahajjud_prayers/videos' . $tahajjud_prayer->video);

        }
        // if ($tahajjud_prayer->audio) {
        //     unlink('uploads/tahajjud_prayers/audio' . $tahajjud_prayer->audio);

        // }
        $tahajjud_prayer->delete();
        return response()->json(['status' => true, 'data' => $tahajjud_prayer]);

    }

    public function statusChange($id): RedirectResponse
    {
        $tahajjud_prayer = $this->query->find($id);
        $status = $tahajjud_prayer->status == 0 ? 1 : 0;
        $tahajjud_prayer->update(['status' => $status]);
        if ($tahajjud_prayer) {
            if ($tahajjud_prayer->status == 1) {
                alert()->success('Tahajjud  Prayer Module', 'Item Status Is Active');
            }
            if ($tahajjud_prayer->status == 0) {
                alert()->success('Tahajjud  Prayer Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Tahajjud  Prayer Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.tahajjud_prayer.index');
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
            if (!file_exists('uploads/tahajjud_prayers/videos')) {
                mkdir('uploads/tahajjud_prayers/videos', 0777, true);
            }
            $file->move('uploads/tahajjud_prayers/videos', $fileName);
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
            if (!file_exists('uploads/tahajjud_prayers/audio')) {
                mkdir('uploads/tahajjud_prayers/audio', 0777, true);
            }
            $file->move('uploads/tahajjud_prayers/audio', $fileName);
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
