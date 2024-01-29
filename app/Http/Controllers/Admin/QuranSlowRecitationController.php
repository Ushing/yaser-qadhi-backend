<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\Lecture;
use App\Models\QuranRecitation;
use App\Models\QuranSlowRecitation;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
use App\Query\QuranRecitationQuery;
use App\Query\QuranSlowRecitationQuery;
use App\Query\SurahRecitationQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class QuranSlowRecitationController extends Controller
{
    protected QuranSlowRecitationQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-recitations-slow.';

    public function __construct(QuranSlowRecitationQuery $quranRecitationQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran_slow_recitations';
        $this->query = $quranRecitationQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran Slow Recitation',
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
            'moduleName' => 'Quran Slow Recitation Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {
        $quranRecitation = $this->query->saveQuranRecitation($request);
        if ($quranRecitation) {
            alert()->success('Quran Slow Recitation', 'Item Created Successfully');
            return redirect()->route('admin.quran_slow_recitations.index');
        } else {
            alert()->error('Quran Slow Recitation', 'Failed To Create');
            return redirect()->route('admin.quran_slow_recitations.index');
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
            'moduleName' => 'Quran Slow Recitation Details',
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
            'moduleName' => 'Quran Slow Recitation Edit',
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
    public function update(SurahRecitationRequest $request,  $id): RedirectResponse
    {
        $content = QuranSlowRecitation::find($id);
        $quranRecitation = $this->query->updateQuranRecitation($request, $content);
        if ($quranRecitation) {
            alert()->success('Quran Slow Recitation', 'Item Updated Successfully');
            return redirect()->route('admin.quran_slow_recitations.index');
        } else {
            alert()->error('Quran Slow Recitation', 'Failed To Update');
            return redirect()->route('admin.quran_slow_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranRecitation = $this->query->find($id);
        if ($quranRecitation->video) {
            unlink('uploads/quranSlowRecitations/videos/' . $quranRecitation->video);

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
                alert()->success('Quran Slow Recitation Module', 'Item Status Is Active');
            }
            if ($quranRecitation->status == 0) {
                alert()->success('Quran Slow Recitation Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Quran Slow Recitation Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.quran_slow_recitations.index');
    }

    public function orderView(): View
    {
        $lectures = Lecture::orderBy('position', 'asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('lectures'));
    }

    public function reOrderList(Request $request): Response
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('lectures')
                ->where('id', $id)
                ->update([
                    'position' => $index + 1
                ]);
        }
        return response('Update Successfully.', 200);
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
            if (!file_exists('uploads/quranSlowRecitations/videos')) {
                mkdir('uploads/quranSlowRecitations/videos', 0777, true);
            }
            $file->move('uploads/quranSlowRecitations/videos', $fileName);
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
            if (!file_exists('uploads/quranSlowRecitations/audio')) {
                mkdir('uploads/quranSlowRecitations/audio', 0777, true);
            }
            $file->move('uploads/quranSlowRecitations/audio', $fileName);
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
