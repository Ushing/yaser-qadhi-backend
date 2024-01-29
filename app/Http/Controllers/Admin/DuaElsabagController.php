<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\DuaElsabag;
use App\Models\KhatiraLectureRecitation;
use App\Models\Lecture;
use App\Query\DuaElsabagQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class DuaElsabagController extends Controller
{
    protected DuaElsabagQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.dua-elsabags.';

    public function __construct(DuaElsabagQuery $duaElsabagQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/dua_elsabags';
        $this->query = $duaElsabagQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Dua',
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
            'moduleName' => 'Dua Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {

        $duaElsabag = $this->query->saveRecitation($request);
        if ($duaElsabag) {
            alert()->success('Dua', 'Item Created Successfully');
            return redirect()->route('admin.dua_elsabags.index');
        } else {
            alert()->error('Dua', 'Failed To Create');
            return redirect()->route('admin.dua_elsabags.index');
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
        $duaElsabag = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Details',
            'duaElsabag' => $duaElsabag,
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
        $duaElsabag = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua Edit',
            'duaElsabag' => $duaElsabag,

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
        $content = $this->query->find($id);
        $duaElsabag = $this->query->updateRecitation($request, $content);
        if ($duaElsabag) {
            alert()->success('Dua', 'Item Updated Successfully');
            return redirect()->route('admin.dua_elsabags.index');
        } else {
            alert()->error('Dua', 'Failed To Update');
            return redirect()->route('admin.dua_elsabags.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $duaElsabag = $this->query->find($id);
        if ($duaElsabag->video) {
            unlink('uploads/duaElsabags/videos/' . $duaElsabag->video);

        }
        $duaElsabag->delete();
        return response()->json(['status' => true, 'data' => $duaElsabag]);

    }


    public function statusChange($id): RedirectResponse
    {
        $duaElsabag = $this->query->find($id);
        $status = $duaElsabag->status == 0 ? 1 : 0;
        $duaElsabag->update(['status' => $status]);
        if ($duaElsabag) {
            if ($duaElsabag->status == 1) {
                alert()->success('Dua Module', 'Item Status Is Active');
            }
            if ($duaElsabag->status == 0) {
                alert()->success('Dua Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Dua Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.dua_elsabags.index');
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
            if (!file_exists('uploads/duaElsabags/videos')) {
                mkdir('uploads/duaElsabags/videos', 0777, true);
            }
            $file->move('uploads/duaElsabags/videos', $fileName);
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
            if (!file_exists('uploads/duaElsabags/audio')) {
                mkdir('uploads/duaElsabags/audio', 0777, true);
            }
            $file->move('uploads/duaElsabags/audio', $fileName);
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
