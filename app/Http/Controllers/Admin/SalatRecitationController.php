<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalatRecitationRequest;
use App\Models\IslamicSong;
use App\Models\SalatRecitation;
use App\Query\SalatQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class SalatRecitationController extends Controller
{
    protected SalatQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.salats.';

    public function __construct(SalatQuery $salatQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/salat_recitations';
        $this->query = $salatQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Salat Recitation',
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
            'moduleName' => 'Salat Recitation Create',
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SalatRecitationRequest $request): RedirectResponse
    {
        $salat = $this->query->saveData($request);
        if ($salat) {
            alert()->success('Salat Recitation', 'Item Created Successfully');
            return redirect()->route('admin.salat_recitations.index');
        } else {
            alert()->error('Salat Recitation', 'Failed To Create');
            return redirect()->route('admin.salat_recitations.index');
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
        $salat = $this->query->find($id);
        $data = [
            'moduleName' => 'Salat Recitation Details',
            'salat' => $salat,
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
        $salat = $this->query->find($id);
        $data = [
            'moduleName' => 'Salat Recitation Edit',
            'salat' => $salat,
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
    public function update(SalatRecitationRequest $request, SalatRecitation $salat):RedirectResponse
    {
        $salat = $this->query->updateData($request, $salat);
        if ($salat) {
            alert()->success('Salat Recitation', 'Item Updated Successfully');
            return redirect()->route('admin.salat_recitations.index');
        } else {
            alert()->error('Salat Recitation', 'Failed To Update');
            return redirect()->route('admin.salat_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $salat = $this->query->find($id);
        $salat->delete();
        return response()->json(['status' => true, 'data' => $salat]);
    }


    public function statusChange($id): RedirectResponse
    {
        $salat = $this->query->find($id);
        $status = $salat->status == 0 ? 1 : 0;
        $salat->update(['status' => $status]);
        if ($salat) {
            if ($salat->status == 1) {
                alert()->success('Salat Recitation Module', 'Item Status Is Active');
            }
            if ($salat->status == 0) {
                alert()->success('Salat Recitation Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Salat Recitation Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.salat_recitations.index');
    }

    public function orderView():View
    {
        $lectures =  IslamicSong::orderBy('position','asc')->get();
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
            if (!file_exists('uploads/salats/audios')){
                mkdir('uploads/salats/audios', 0777, true);
            }
            $file->move('uploads/salats/audios', $fileName);
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
            if (!file_exists('uploads/salats/videos')){
                mkdir('uploads/salats/videos', 0777, true);
            }
            $file->move('uploads/salats/videos', $fileName);
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
