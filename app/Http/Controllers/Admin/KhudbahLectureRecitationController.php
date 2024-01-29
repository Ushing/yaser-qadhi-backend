<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\KhudbahLectureRecitation;
use App\Models\Lecture;
use App\Query\KhudbahLectureRecitationQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class KhudbahLectureRecitationController extends Controller
{
    protected KhudbahLectureRecitationQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.khudbah-lecture.';

    public function __construct(KhudbahLectureRecitationQuery $khudbahLectureRecitationQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/khudbah_lecture_recitations';
        $this->query = $khudbahLectureRecitationQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Khudbah Lecture',
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
            'moduleName' => 'Khudbah Lecture Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {

        $khudbahLecture = $this->query->saveRecitation($request);
        if ($khudbahLecture) {
            alert()->success('Khudbah Lecture', 'Item Created Successfully');
            return redirect()->route('admin.khudbah_lecture_recitations.index');
        } else {
            alert()->error('Khudbah Lecture', 'Failed To Create');
            return redirect()->route('admin.khudbah_lecture_recitations.index');
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
        $khudbahLecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Khudbah Lecture Details',
            'khudbahLecture' => $khudbahLecture,
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
        $khudbahLecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Khudbah Lecture Edit',
            'khudbahLecture' => $khudbahLecture,

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
        $content = KhudbahLectureRecitation::find($id);
        $khudbahLecture = $this->query->updateRecitation($request, $content);
        if ($khudbahLecture) {
            alert()->success('Khudbah Lecture', 'Item Updated Successfully');
            return redirect()->route('admin.khudbah_lecture_recitations.index');
        } else {
            alert()->error('Khudbah Lecture', 'Failed To Update');
            return redirect()->route('admin.khudbah_lecture_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $khudbahLecture = $this->query->find($id);
        if ($khudbahLecture->video) {
            unlink('uploads/khudbahLectures/videos/' . $khudbahLecture->video);

        }
        $khudbahLecture->delete();
        return response()->json(['status' => true, 'data' => $khudbahLecture]);

    }


    public function statusChange($id): RedirectResponse
    {
        $khudbahLecture = $this->query->find($id);
        $status = $khudbahLecture->status == 0 ? 1 : 0;
        $khudbahLecture->update(['status' => $status]);
        if ($khudbahLecture) {
            if ($khudbahLecture->status == 1) {
                alert()->success('Khudbah Lecture Module', 'Item Status Is Active');
            }
            if ($khudbahLecture->status == 0) {
                alert()->success('Khudbah Lecture Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Khudbah Lecture Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.khudbah_lecture_recitations.index');
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
            if (!file_exists('uploads/khudbahLectures/videos')) {
                mkdir('uploads/khudbahLectures/videos', 0777, true);
            }
            $file->move('uploads/khudbahLectures/videos', $fileName);
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
            if (!file_exists('uploads/khudbahLectures/audio')) {
                mkdir('uploads/khudbahLectures/audio', 0777, true);
            }
            $file->move('uploads/khudbahLectures/audio', $fileName);
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
