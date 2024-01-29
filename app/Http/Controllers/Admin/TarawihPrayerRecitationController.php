<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahRecitationRequest;
use App\Models\Lecture;
use App\Models\SurahRecitation;
use App\Models\TarawihPrayerRecitation;
use App\Query\TarawihPrayerRecitationQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class TarawihPrayerRecitationController extends Controller
{
    protected TarawihPrayerRecitationQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.tarawih-recitations.';

    public function __construct(TarawihPrayerRecitationQuery $tarawihPrayerRecitationQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/tarawih_prayer_recitations';
        $this->query = $tarawihPrayerRecitationQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Tarawih Recitation',
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
            'moduleName' => 'Tarawih Recitation Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(SurahRecitationRequest $request): RedirectResponse
    {

        $tarawihRecitation = $this->query->saveRecitation($request);
        if ($tarawihRecitation) {
            alert()->success('Tarawih Recitation', 'Item Created Successfully');
            return redirect()->route('admin.tarawih_prayer_recitations.index');
        } else {
            alert()->error('Tarawih Recitation', 'Failed To Create');
            return redirect()->route('admin.tarawih_prayer_recitations.index');
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
        $tarawihRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Tarawih Recitation Details',
            'tarawihRecitation' => $tarawihRecitation,
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
        $tarawihRecitation = $this->query->find($id);
        $data = [
            'moduleName' => 'Tarawih Recitation Edit',
            'tarawihRecitation' => $tarawihRecitation,

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
        $content = TarawihPrayerRecitation::find($id);
        $tarawihRecitation = $this->query->updateRecitation($request, $content);
        if ($tarawihRecitation) {
            alert()->success('Tarawih Recitation', 'Item Updated Successfully');
            return redirect()->route('admin.tarawih_prayer_recitations.index');
        } else {
            alert()->error('Tarawih Recitation', 'Failed To Update');
            return redirect()->route('admin.tarawih_prayer_recitations.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $tarawihRecitation = $this->query->find($id);
        if ($tarawihRecitation->video) {
            unlink('uploads/tarawihRecitations/videos/' . $tarawihRecitation->video);

        }
        $tarawihRecitation->delete();
        return response()->json(['status' => true, 'data' => $tarawihRecitation]);

    }


    public function statusChange($id): RedirectResponse
    {
        $tarawihRecitation = $this->query->find($id);
        $status = $tarawihRecitation->status == 0 ? 1 : 0;
        $tarawihRecitation->update(['status' => $status]);
        if ($tarawihRecitation) {
            if ($tarawihRecitation->status == 1) {
                alert()->success('Tarawih Recitation Module', 'Item Status Is Active');
            }
            if ($tarawihRecitation->status == 0) {
                alert()->success('Tarawih Recitation Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Tarawih Recitation Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.tarawih_prayer_recitations.index');
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
            if (!file_exists('uploads/tarawihRecitations/videos')) {
                mkdir('uploads/tarawihRecitations/videos', 0777, true);
            }
            $file->move('uploads/tarawihRecitations/videos', $fileName);
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
            if (!file_exists('uploads/tarawihRecitations/audio')) {
                mkdir('uploads/tarawihRecitations/audio', 0777, true);
            }
            $file->move('uploads/tarawihRecitations/audio', $fileName);
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
