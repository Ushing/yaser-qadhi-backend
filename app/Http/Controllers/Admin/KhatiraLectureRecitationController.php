<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\KhatiraLectureRecitation;
use App\Models\Lecture;
use App\Query\KhatiraLectureRecitationQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class KhatiraLectureRecitationController extends Controller
{
    protected KhatiraLectureRecitationQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.khatira-lecture.';

    public function __construct(KhatiraLectureRecitationQuery $khatiraLectureRecitationQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/khatira_lecture_recitations';
        $this->query = $khatiraLectureRecitationQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Khatira Lecture',
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
            'moduleName' => 'Khatira Lecture Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {

        $khatiraLecture = $this->query->saveRecitation($request);
        if ($khatiraLecture) {
            alert()->success('Khatira Lecture', 'Item Created Successfully');
            return redirect()->route('admin.khatira_lecture_recitations.index');
        } else {
            alert()->error('Khatira Lecture', 'Failed To Create');
            return redirect()->route('admin.khatira_lecture_recitations.index');
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
        $khatiraLecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Khatira Lecture Details',
            'khatiraLecture' => $khatiraLecture,
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
        $khatiraLecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Khatira Lecture Edit',
            'khatiraLecture' => $khatiraLecture,

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
    public function update(SurahRecitationRequest $request, $id): RedirectResponse
    {
        $content = KhatiraLectureRecitation::find($id);
        $khatiraLecture = $this->query->updateRecitation($request, $content);
        if ($khatiraLecture) {
            alert()->success('Khatira Lecture', 'Item Updated Successfully');
            return redirect()->route('admin.khatira_lecture_recitations.index');
        } else {
            alert()->error('Khatira Lecture', 'Failed To Update');
            return redirect()->route('admin.khatira_lecture_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $khatiraLecture = $this->query->find($id);
        if ($khatiraLecture->video) {
            unlink('uploads/khatiraLectures/videos/' . $khatiraLecture->video);

        }
        $khatiraLecture->delete();
        return response()->json(['status' => true, 'data' => $khatiraLecture]);

    }


    public function statusChange($id): RedirectResponse
    {
        $khatiraLecture = $this->query->find($id);
        $status = $khatiraLecture->status == 0 ? 1 : 0;
        $khatiraLecture->update(['status' => $status]);
        if ($khatiraLecture) {
            if ($khatiraLecture->status == 1) {
                alert()->success('Khatira Lecture Module', 'Item Status Is Active');
            }
            if ($khatiraLecture->status == 0) {
                alert()->success('Khatira Lecture Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Khatira Lecture Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.khatira_lecture_recitations.index');
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
            if (!file_exists('uploads/khatiraLectures/videos')) {
                mkdir('uploads/khatiraLectures/videos', 0777, true);
            }
            $file->move('uploads/khatiraLectures/videos', $fileName);
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
            if (!file_exists('uploads/khatiraLectures/audio')) {
                mkdir('uploads/khatiraLectures/audio', 0777, true);
            }
            $file->move('uploads/khatiraLectures/audio', $fileName);
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
