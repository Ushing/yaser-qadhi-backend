<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\Lecture;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
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


class SurahRecitationController extends Controller
{
    protected SurahRecitationQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.surah-recitations.';

    public function __construct(SurahRecitationQuery $surahRecitationQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/surah_recitations';
        $this->query = $surahRecitationQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Surah Recitation',
            'tableHeads' => ['Sr. No','Reference ID', 'Title','Status', 'Change Status','Sub Title(Files)','Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'reference_id', 'name'=>'reference_id'],
                ['data' => 'title', 'name' => 'title'],
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
            'moduleName' => 'Surah Recitation Create',
            'surahs'=> DB::table('surahs')->orderBy('id','asc')->get(),
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {
        $surahRecitation = $this->query->saveSurahRecitation($request);
        if ($surahRecitation) {
            alert()->success('Surah Recitation', 'Item Created Successfully');
            return redirect()->route('admin.surah_recitations.index');
        } else {
            alert()->error('Surah Recitation', 'Failed To Create');
            return redirect()->route('admin.surah_recitations.index');
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
        $surahRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Surah Recitation Details',
            'surahRecitation' => $surahRecitation,
            'surahRecitationFiles'=> SurahReciteFile::where('surah_recitation_id',$id)->get()
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
        $surahRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Surah Recitation Edit',
            'surahRecitation' => $surahRecitation,
            'surahs'=> DB::table('surahs')->orderBy('id','asc')->get(),

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
    public function update(SurahRecitationRequest $request, SurahRecitation $surahRecitation):RedirectResponse
    {
        $surahRecitation = $this->query->updateSurahRecitation($request, $surahRecitation);
        if ($surahRecitation) {
            alert()->success('Surah Recitation', 'Item Updated Successfully');
            return redirect()->route('admin.surah_recitations.index');
        } else {
            alert()->error('Surah Recitation', 'Failed To Update');
            return redirect()->route('admin.surah_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $surahRecitation = $this->query->find($id);
        if ($surahRecitation->audio) {
            unlink('uploads/surah/audios/'.$surahRecitation->audio);
        }
        if ($surahRecitation->video) {
            unlink('uploads/surah/videos/'.$surahRecitation->video);

        }
        $surahRecitation->surahReciteFiles()->delete();
        $surahRecitation->delete();
        return response()->json(['status' => true, 'data' => $surahRecitation]);

    }


    public function statusChange($id): RedirectResponse
    {
        $surahRecitation = $this->query->find($id);
        $status = $surahRecitation->status == 0 ? 1 : 0;
        $surahRecitation->update(['status' => $status]);
        if ($surahRecitation) {
            if ($surahRecitation->status == 1) {
                alert()->success('Surah Recitation Module', 'Item Status Is Active');
            }
            if ($surahRecitation->status == 0) {
                alert()->success('Surah Recitation Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Surah Recitation Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.surah_recitations.index');
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



    public function uploadSurahRecitationAudio(Request $request): array
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
            if (!file_exists('uploads/surah/audios')){
                mkdir('uploads/surah/audios', 0777, true);
            }
       $file->move('uploads/surah/audios', $fileName);
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


    public function uploadSurahRecitationVideo(Request $request): array
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
            if (!file_exists('uploads/surah/videos')){
                mkdir('uploads/surah/videos', 0777, true);
            }
            $file->move('uploads/surah/videos', $fileName);
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
