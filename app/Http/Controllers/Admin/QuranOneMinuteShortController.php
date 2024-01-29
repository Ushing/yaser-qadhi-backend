<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuranShortRequest;
use App\Models\Lecture;
use App\Models\QuranOneMinuteShort;
use App\Query\QuranInterviewQuery;
use App\Query\QuranShortQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class QuranOneMinuteShortController extends Controller
{
    protected QuranShortQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-shorts.';

    public function __construct(QuranShortQuery $quranShortQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran_shorts';
        $this->query = $quranShortQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran One Minute Short',
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
            'moduleName' => 'Quran One Minute Short Create',
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(QuranShortRequest $request): RedirectResponse
    {
        $quranShort = $this->query->saveQuranShort($request);
        if ($quranShort) {
            alert()->success('Quran One Minute Short', 'Item Created Successfully');
            return redirect()->route('admin.quran_shorts.index');
        } else {
            alert()->error('Quran One Minute Short', 'Failed To Create');
            return redirect()->route('admin.quran_shorts.index');
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
        $quranShort = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran One Minute Short Details',
            'quranShort' => $quranShort,
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
        $quranShort = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran One Minute Short Edit',
            'quranShort' => $quranShort,
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
    public function update(QuranShortRequest $request, QuranOneMinuteShort $quranShort):RedirectResponse
    {
        $quranShort = $this->query->updateQuranShort($request, $quranShort);
        if ($quranShort) {
            alert()->success('Quran One Minute Short', 'Item Updated Successfully');
            return redirect()->route('admin.quran_shorts.index');
        } else {
            alert()->error('Quran One Minute Short', 'Failed To Update');
            return redirect()->route('admin.quran_shorts.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranShort = $this->query->find($id);
        $quranShort->quranOneMinuteShortFiles()->delete();
        $quranShort->delete();
        return response()->json(['status' => true, 'data' => $quranShort]);
    }


    public function statusChange($id): RedirectResponse
    {
        $quranShort = $this->query->find($id);
        $status = $quranShort->status == 0 ? 1 : 0;
        $quranShort->update(['status' => $status]);
        if ($quranShort) {
            if ($quranShort->status == 1) {
                alert()->success('Quran One Minute Short Module', 'Item Status Is Active');
            }
            if ($quranShort->status == 0) {
                alert()->success('Quran One Minute Short Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Quran One Minute Short Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.quran_shorts.index');
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
            if (!file_exists('uploads/quranShorts/audios')){
                mkdir('uploads/quranShorts/audios', 0777, true);
            }
            $file->move('uploads/quranShorts/audios', $fileName);
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
            if (!file_exists('uploads/quranShorts/videos')){
                mkdir('uploads/quranShorts/videos', 0777, true);
            }
            $file->move('uploads/quranShorts/videos', $fileName);
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
