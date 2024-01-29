<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuranProgramRequest;
use App\Models\Lecture;
use App\Models\QuranProgramCategory;
use App\Models\QuranProgramFiles;
use App\Models\QuranProgramList;
use App\Query\QuranProgramQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class QuranProgramController extends Controller
{
    protected QuranProgramQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.quran-program-lists.';

    public function __construct(QuranProgramQuery $quranProgramQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/quran_program_lists';
        $this->query = $quranProgramQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Quran Program List',
            'tableHeads' => ['Sr. No','Reference ID', 'Title', 'Category','Status', 'Change Status','Sub Title(Files)','Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'reference_id', 'name'=>'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'quran_program_category_id', 'name' => 'quran_program_category_id'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'status_change', 'name' => 'status_change'],
                ['data' => 'add_files', 'name' => 'add_files'],
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
            'moduleName' => 'Quran Program List Create',
            'programCategories'=> QuranProgramCategory::query()->orderBy('id','asc')->get(),
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(QuranProgramRequest $request): RedirectResponse
    {
        $quranProgram = $this->query->saveQuranProgram($request);
        if ($quranProgram) {
            alert()->success('Quran Program List', 'Item Created Successfully');
            return redirect()->route('admin.quran_program_lists.index');
        } else {
            alert()->error('Quran Program List', 'Failed To Create');
            return redirect()->route('admin.quran_program_lists.index');
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
        $quranProgram = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Program List Details',
            'quranProgram' => $quranProgram,
            'quranProgramFiles'=> QuranProgramFiles::where('quran_program_list_id',$id)->get()
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
        $quranProgram = $this->query->find($id);
        $data = [
            'moduleName' => 'Quran Program List Edit',
            'quranProgram' => $quranProgram,
            'programCategories'=> QuranProgramCategory::query()->orderBy('id','asc')->get(),
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
    public function update(QuranProgramRequest $request, QuranProgramList $quranProgramList):RedirectResponse
    {
        $quranProgram = $this->query->updateQuranProgram($request, $quranProgramList);
        if ($quranProgram) {
            alert()->success('Quran Program List', 'Item Updated Successfully');
            return redirect()->route('admin.quran_program_lists.index');
        } else {
            alert()->error('Quran Program List', 'Failed To Update');
            return redirect()->route('admin.quran_program_lists.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $quranProgram = $this->query->find($id);
        if (file_exists('uploads/quranProgram/audios/'.$quranProgram->audio)) {
            unlink('uploads/quranProgram/audios/'.$quranProgram->audio);
        }
        if (file_exists('uploads/quranProgram/videos/'.$quranProgram->video)) {
            unlink('uploads/quranProgram/videos/'.$quranProgram->video);
        }
        $quranProgram->quranProgramFiles()->delete();
        $quranProgram->delete();
        return response()->json(['status' => true, 'data' => $quranProgram]);

    }


    public function statusChange($id): RedirectResponse
    {
        $quranProgram = $this->query->find($id);
        $status = $quranProgram->status == 0 ? 1 : 0;
        $quranProgram->update(['status' => $status]);
        if ($quranProgram) {
            if ($quranProgram->status == 1) {
                alert()->success('Quran Program List Module', 'Item Status Is Active');
            }
            if ($quranProgram->status == 0) {
                alert()->success('Quran Program List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Quran Program List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.quran_program_lists.index');
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
            if (!file_exists('uploads/quranProgram/audios')){
                mkdir('uploads/quranProgram/audios', 0777, true);
            }
            $file->move('uploads/quranProgram/audios', $fileName);
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
            if (!file_exists('uploads/quranProgram/videos')){
                mkdir('uploads/quranProgram/videos', 0777, true);
            }
            $file->move('uploads/quranProgram/videos', $fileName);
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
