<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureRequest;
use App\Models\Dua;
use App\Models\Lecture;
use App\Query\LectureCategoryQuery;
use App\Query\LectureQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class LectureController extends Controller
{
    protected LectureQuery $query;
    protected LectureCategoryQuery $lectureCategoryQuery;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.lectures.';

    public function __construct(LectureQuery $lectureQuery, LectureCategoryQuery $lectureCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/lecture';
        $this->query = $lectureQuery;
        $this->lectureCategoryQuery = $lectureCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('lecture-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Lecture !');
        }
        $data = [
            'moduleName' => 'Lecture',
            'tableHeads' => ['Sr. No','Reference ID', 'Title', 'Lecture Sub Category', 'Description','Position', 'Status', 'Change Status','Tags', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'reference_id', 'name'=>'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'lecture_sub_category_id', 'name' => 'lecture_sub_category_id'],
                ['data' => 'description', 'name' => 'description'],
                ['data' => 'position', 'name' => 'position'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'status_change', 'name' => 'status_change'],
                ['data' => 'add_tag', 'name' => 'add_tag'],
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
        if (is_null($this->user) or !$this->user->can('lecture-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Lecture !');
        }
        $data = [
            'moduleName' => 'Lecture',
            'lectureCategories' => $this->lectureCategoryQuery->getActiveData()
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(LectureRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Lecture !');
        }
        $lecture = $this->query->saveLecture($request);
        if ($lecture) {
            alert()->success('Lecture', 'Item Created Successfully');
            return redirect()->route('admin.lecture.index');
        } else {
            alert()->error('Lecture', 'Failed To Create');
            return redirect()->route('admin.lecture.index');
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
        if (is_null($this->user) or !$this->user->can('lecture-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture !');
        }
        $lecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture  Details',
            'lecture' => $lecture,
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
        if (is_null($this->user) or !$this->user->can('lecture-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Lecture !');
        }
        $lecture = $this->query->find($id);
        $data = [
            'moduleName' => 'Lecture Edit',
            'lectureCategories' => $this->lectureCategoryQuery->getActiveData(),
            'lecture' => $lecture,
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
    public function update(LectureRequest $request, Lecture $lecture):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Lecture !');
        }
        $updateLecture = $this->query->updateLecture($request, $lecture);
        if ($updateLecture) {
            alert()->success('Lecture', 'Item Updated Successfully');
            return redirect()->route('admin.lecture.index');
        } else {
            alert()->error('Lecture', 'Failed To Update');
            return redirect()->route('admin.lecture.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Lecture !');
        }
        $lecture = $this->query->find($id);
        if ($lecture->audio) {
            Storage::disk('s3')->delete('/lecture/audios/' . $lecture->audio);
        }
        if ($lecture->video) {
            Storage::disk('s3')->delete('/lecture/videos/' . $lecture->video);
        }
        $lecture->delete();
        return response()->json(['status' => true, 'data' => $lecture]);
    }


    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('lecture-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $lecture = $this->query->find($id);
        $status = $lecture->status == 0 ? 1 : 0;
        $lecture->update(['status' => $status]);
        if ($lecture) {
            if ($lecture->status == 1) {
                alert()->success('Lecture Module', 'Item Status Is Active');
            }
            if ($lecture->status == 0) {
                alert()->success('Lecture Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Lecture Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.lecture.index');
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


    public function uploadLectureVideo(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            // file not uploaded
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            $filePath = 'lecture/videos/' . $fileName;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            unlink($file->getPathname());
            return [
                'path' =>   Storage::disk('s3')->url($filePath),
                'filename' => $fileName
            ];
        }
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }


    public function uploadLectureAudio(Request $request): array
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            // file not uploaded
        }
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
            $filePath = 'lecture/audios/' . $fileName;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            unlink($file->getPathname());
            return [
                'path' =>   Storage::disk('s3')->url($filePath),
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
