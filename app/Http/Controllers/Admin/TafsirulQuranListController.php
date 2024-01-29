<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TafsirulQuranList;
use App\Query\TafsirulQuranListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class TafsirulQuranListController extends Controller
{
    protected TafsirulQuranListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.tafsirul-quran-list.';

    public function __construct(TafsirulQuranListQuery $TafsirulQuranListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/tafsirul-quran-list';
        $this->query = $TafsirulQuranListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Tafsirul Quran',
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
            'moduleName' => 'Tafsirul Quran Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $tafsirulQuran = $this->query->saveRecitation($request);
        if ($tafsirulQuran) {
            alert()->success('Tafsirul Quran', 'Item Created Successfully');
            return redirect()->route('admin.tafsirul-quran-list.index');
        } else {
            alert()->error('Tafsirul Quran', 'Failed To Create');
            return redirect()->route('admin.tafsirul-quran-list.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Lecture $lecture
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): View
    {
        $tafsirulQuran = $this->query->find($id);
        $data = [
            'moduleName' => 'Tafsirul Quran Details',
            'tafsirulQuran' => $tafsirulQuran,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Lecture $lecture
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): View
    {
        $tafsirulQuran = $this->query->find($id);
        $data = [
            'moduleName' => 'Tafsirul Quran Edit',
            'tafsirulQuran' => $tafsirulQuran,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Lecture $lecture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $content = TafsirulQuranList::find($id);
        $tafsirulQuran = $this->query->updateRecitation($request, $content);
        if ($tafsirulQuran) {
            alert()->success('Tafsirul Quran', 'Item Updated Successfully');
            return redirect()->route('admin.tafsirul-quran-list.index');
        } else {
            alert()->error('Tafsirul Quran', 'Failed To Update');
            return redirect()->route('admin.tafsirul-quran-list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $tafsirulQuran = $this->query->find($id);
        if ($tafsirulQuran->video) {
            unlink('uploads/QuranRecitation/videos/' . $tafsirulQuran->video);

        }
        $tafsirulQuran->delete();
        return response()->json(['status' => true, 'data' => $tafsirulQuran]);

    }


    public function statusChange($id): RedirectResponse
    {
        $tafsirulQuran = $this->query->find($id);
        $status = $tafsirulQuran->status == 0 ? 1 : 0;
        $tafsirulQuran->update(['status' => $status]);
        if ($tafsirulQuran) {
            if ($tafsirulQuran->status == 1) {
                alert()->success('Tafsirul Quran Module', 'Item Status Is Active');
            }
            if ($tafsirulQuran->status == 0) {
                alert()->success('Tafsirul Quran Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Tafsirul Quran Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.tafsirul-quran-list.index');
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
            if (!file_exists('uploads/TafsirulQuran/videos')) {
                mkdir('uploads/TafsirulQuran/videos', 0777, true);
            }
            $file->move('uploads/TafsirulQuran/videos', $fileName);
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
            if (!file_exists('uploads/TafsirulQuran/audio')) {
                mkdir('uploads/TafsirulQuran/audio', 0777, true);
            }
            $file->move('uploads/TafsirulQuran/audio', $fileName);
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
