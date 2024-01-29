<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RamadanSeries;
use App\Query\RamadanSeriesQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;

class ramadanSeriesController extends Controller
{
    protected RamadanSeriesQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.ramadan_series.';

    public function __construct(RamadanSeriesQuery $RamadanSeriesQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/ramadan_series';
        $this->query = $RamadanSeriesQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Ramadan Series List',
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
            'moduleName' => 'Ramadan Series List Create',
        ];

        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(Request $request): RedirectResponse
    {

        $RamadanSerieslist = $this->query->saveRecitation($request);
        if ($RamadanSerieslist) {
            alert()->success('Ramadan Series List', 'Item Created Successfully');
            return redirect()->route('admin.ramadan_series.index');
        } else {
            alert()->error('Ramadan Series List', 'Failed To Create');
            return redirect()->route('admin.ramadan_series.index');
        }
    }
    public function show(int $id): View
    {
        $RamadanSerieslist = $this->query->find($id);
        $data = [
            'moduleName' => 'Ramadan Series List Details',
            'ramadan_series' => $RamadanSerieslist,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    public function edit(int $id): View
    {
        $RamadanSerieslist = $this->query->find($id);
        $data = [
            'moduleName' => 'Ramadan Series List Edit',
            'ramadan_series' => $RamadanSerieslist,

        ];
        return view(self::moduleDirectory . 'edit', $data);
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $content = RamadanSeries::find($id);
        $RamadanSerieslist = $this->query->updateRecitation($request, $content);
        if ($RamadanSerieslist) {
            alert()->success('Ramadan Series List', 'Item Updated Successfully');
            return redirect()->route('admin.ramadan_series.index');
        } else {
            alert()->error('Ramadan Series List', 'Failed To Update');
            return redirect()->route('admin.ramadan_series.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $RamadanSerieslist = $this->query->find($id);

        $RamadanSerieslist->delete();
        return response()->json(['status' => true, 'data' => $RamadanSerieslist]);

    }

    public function statusChange($id): RedirectResponse
    {
        $RamadanSerieslist = $this->query->find($id);
        $status = $$RamadanSerieslist->status == 0 ? 1 : 0;
        $RamadanSerieslist->update(['status' => $status]);
        if ($RamadanSerieslist) {
            if ($RamadanSerieslist->status == 1) {
                alert()->success('Ramadan Series List Module', 'Item Status Is Active');
            }
            if ($RamadanSerieslist->status == 0) {
                alert()->success('Ramadan Series List Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Ramadan Series List Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.ramadan_series.index');
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
            if (!file_exists('uploads/RamadanSerieslist/videos')) {
                mkdir('uploads/RamadanSerieslist/videos', 0777, true);
            }
            $file->move('uploads/RamadanSerieslist/videos', $fileName);
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
            if (!file_exists('uploads/RamadanSerieslist/audio')) {
                mkdir('uploads/RamadanSerieslist/audio', 0777, true);
            }
            $file->move('uploads/RamadanSerieslist/audio', $fileName);
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
