<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuranInterviewRequest;
use App\Models\Lecture;
use App\Models\QuranInterview;
use App\Query\QuranInterviewQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class QuranInterviewController extends Controller
{
    protected QuranInterviewQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-interviews.';

    public function __construct(QuranInterviewQuery $quranInterviewQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran_interviews';
        $this->query = $quranInterviewQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran Interview',
            'tableHeads' => ['Sr. No','Reference ID', 'Title','Status', 'Change Status','Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'reference_id', 'name'=>'reference_id'],
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
            'moduleName' => 'Quran Interview Create',
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(QuranInterviewRequest $request): RedirectResponse
    {
        $quranInterview = $this->query->saveQuranInterview($request);
        if ($quranInterview) {
            alert()->success('Quran Interview', 'Item Created Successfully');
            return redirect()->route('admin.quran_interviews.index');
        } else {
            alert()->error('Quran Interview', 'Failed To Create');
            return redirect()->route('admin.quran_interviews.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): View
    {
        $quranInterview = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Interview Details',
            'quranInterview' => $quranInterview,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View
    {
        $quranInterview = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Interview Edit',
            'quranInterview' => $quranInterview,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function update(QuranInterviewRequest $request, QuranInterview $quranInterview):RedirectResponse
    {
        $quranInterview = $this->query->updateQuranInterview($request, $quranInterview);
        if ($quranInterview) {
            alert()->success('Quran Interview', 'Item Updated Successfully');
            return redirect()->route('admin.quran_interviews.index');
        } else {
            alert()->error('Quran Interview', 'Failed To Update');
            return redirect()->route('admin.quran_interviews.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranInterview = $this->query->find($id);
        $quranInterview->quranInterviewFiles()->delete();
        $quranInterview->delete();
        return response()->json(['status' => true, 'data' => $quranInterview]);
    }


    public function statusChange($id): RedirectResponse
    {
        $quranInterview = $this->query->find($id);
        $status = $quranInterview->status == 0 ? 1 : 0;
        $quranInterview->update(['status' => $status]);
        if ($quranInterview) {
            if ($quranInterview->status == 1) {
                alert()->success('Quran Interview Module', 'Item Status Is Active');
            }
            if ($quranInterview->status == 0) {
                alert()->success('Quran Interview Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Quran Interview Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.quran_interviews.index');
    }

    public function orderView():View
    {
        $lectures =  Lecture::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('lectures'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
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



    public function uploadAudio(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            if (!file_exists('uploads/quranInterview/audios')){
                mkdir('uploads/quranInterview/audios', 0777, true);
            }
            $file->move('uploads/quranInterview/audios', $fileName);
            return [
                'filename' => $fileName
            ];
        }
        // otherwise return percentage informatoin
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }


    public function uploadVideo(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            if (!file_exists('uploads/quranInterview/videos')){
                mkdir('uploads/quranInterview/videos', 0777, true);
            }
            $file->move('uploads/quranInterview/videos', $fileName);
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
