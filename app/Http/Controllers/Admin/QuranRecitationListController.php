<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuranRecitationList;
use App\Query\QuranRecitationListQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class QuranRecitationListController extends Controller
{
    protected QuranRecitationListQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-recitation-list.';

    public function __construct(QuranRecitationListQuery $QuranRecitationListQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran-recitation-list';
        $this->query = $QuranRecitationListQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran Recitation',
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
            'moduleName' => 'Quran Recitation Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $quranRecitation = $this->query->saveRecitation($request);
        if ($quranRecitation) {
            alert()->success('Quran Recitation', 'Item Created Successfully');
            return redirect()->route('admin.quran-recitation-list.index');
        } else {
            alert()->error('Quran Recitation', 'Failed To Create');
            return redirect()->route('admin.quran-recitation-list.index');
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
        $quranRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Recitation Details',
            'quranRecitation' => $quranRecitation,
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
        $quranRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Recitation Edit',
            'quranRecitation' => $quranRecitation,

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
        $content = QuranRecitationList::find($id);
        $quranRecitation = $this->query->updateRecitation($request, $content);
        if ($quranRecitation) {
            alert()->success('Quran Recitation', 'Item Updated Successfully');
            return redirect()->route('admin.quran-recitation-list.index');
        } else {
            alert()->error('Quran Recitation', 'Failed To Update');
            return redirect()->route('admin.quran-recitation-list.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranRecitation = $this->query->find($id);
        if ($quranRecitation->video) {
            unlink('uploads/QuranRecitation/video/' . $quranRecitation->video);

        }
        $quranRecitation->delete();
        return response()->json(['status' => true, 'data' => $quranRecitation]);

    }


    public function statusChange($id): RedirectResponse
    {
        $quranRecitation = $this->query->find($id);
        $status = $quranRecitation->status == 0 ? 1 : 0;
        $quranRecitation->update(['status' => $status]);
        if ($quranRecitation) {
            if ($quranRecitation->status == 1) {
                alert()->success('Quran Recitation Module', 'Item Status Is Active');
            }
            if ($quranRecitation->status == 0) {
                alert()->success('Quran Recitation Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Quran Recitation Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.quran-recitation-list.index');
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
            if (!file_exists('uploads/QuranRecitation/video')) {
                mkdir('uploads/QuranRecitation/video', 0777, true);
            }
            $file->move('uploads/QuranRecitation/video', $fileName);
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
            if (!file_exists('uploads/QuranRecitation/audio')) {
                mkdir('uploads/QuranRecitation/audio', 0777, true);
            }
            $file->move('uploads/QuranRecitation/audio', $fileName);
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
