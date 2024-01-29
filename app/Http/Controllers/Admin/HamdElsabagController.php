<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\HamdElsabag;
use App\Models\KhatiraLectureRecitation;
use App\Models\Lecture;
use App\Query\HamdElsabagQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class HamdElsabagController extends Controller
{
    protected HamdElsabagQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.hamd-elsabags.';

    public function __construct(HamdElsabagQuery $hamdElsabagQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/hamd_elsabags';
        $this->query = $hamdElsabagQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Hamd & Nath',
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
            'moduleName' => 'Hamd Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {

        $hamdElsabag = $this->query->saveRecitation($request);
        if ($hamdElsabag) {
            alert()->success('Hamd & Nath', 'Item Created Successfully');
            return redirect()->route('admin.hamd_elsabags.index');
        } else {
            alert()->error('Hamd & Nath', 'Failed To Create');
            return redirect()->route('admin.hamd_elsabags.index');
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
        $hamdElsabag = $this->query->find($id);
        $data = [
            'moduleName' => 'Hamd Details',
            'hamdElsabag' => $hamdElsabag,
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
        $hamdElsabag = $this->query->find($id);
        $data = [
            'moduleName' => 'Hamd Edit',
            'hamdElsabag' => $hamdElsabag,

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
        $hamdElsabag = $this->query->updateRecitation($request, $content);
        if ($hamdElsabag) {
            alert()->success('Hamd & Nath', 'Item Updated Successfully');
            return redirect()->route('admin.hamd_elsabags.index');
        } else {
            alert()->error('Hamd & Nath', 'Failed To Update');
            return redirect()->route('admin.hamd_elsabags.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $hamdElsabag = $this->query->find($id);
        if ($hamdElsabag->video) {
            unlink('uploads/hamdElsabags/videos/' . $hamdElsabag->video);

        }
        $hamdElsabag->delete();
        return response()->json(['status' => true, 'data' => $hamdElsabag]);

    }


    public function statusChange($id): RedirectResponse
    {
        $hamdElsabag = $this->query->find($id);
        $status = $hamdElsabag->status == 0 ? 1 : 0;
        $hamdElsabag->update(['status' => $status]);
        if ($hamdElsabag) {
            if ($hamdElsabag->status == 1) {
                alert()->success('Hamd Module', 'Item Status Is Active');
            }
            if ($hamdElsabag->status == 0) {
                alert()->success('Hamd Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Hamd Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.hamd_elsabags.index');
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
            if (!file_exists('uploads/hamdElsabags/videos')) {
                mkdir('uploads/hamdElsabags/videos', 0777, true);
            }
            $file->move('uploads/hamdElsabags/videos', $fileName);
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
            if (!file_exists('uploads/hamdElsabags/audio')) {
                mkdir('uploads/hamdElsabags/audio', 0777, true);
            }
            $file->move('uploads/hamdElsabags/audio', $fileName);
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
