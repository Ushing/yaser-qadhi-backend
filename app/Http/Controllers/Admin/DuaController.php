<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DuaRequest;
use App\Models\Dua;
use App\Query\DuaCategoryQuery;
use App\Query\DuaQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class DuaController extends Controller
{
    protected DuaQuery $query;
    protected DuaCategoryQuery $duaCategoryQuery;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.duas.';

    public function __construct(DuaQuery $duaQuery, DuaCategoryQuery $duaCategoryQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/dua';
        $this->query = $duaQuery;
        $this->duaCategoryQuery = $duaCategoryQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('dua-view')) {
            abort(403, 'Sorry!! You are Unauthorized To Access Dua !');
        }
        $data = [
            'moduleName' => 'Lists Of Dua',
            'tableHeads' => ['Sr. No','Reference ID', 'Title', 'Dua Sub Category', 'Translation', 'Transliteration','Position', 'Status', 'Change Status', 'Tags', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'reference_id', 'name' => 'reference_id'],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'dua_sub_category_id', 'name' => 'dua_sub_category_id'],
                ['data' => 'translation', 'name' => 'translation'],
                ['data' => 'transliteration', 'name' => 'transliteration'],
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
        if (is_null($this->user) or !$this->user->can('dua-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Create Dua !');
        }
        $data = [
            'moduleName' => 'Dua',
            'duaCategories' => $this->duaCategoryQuery->getActiveData(),
            'dua'=> $this->query->getActiveData()
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(DuaRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-create')) {
            abort(403, 'Sorry!! You are Unauthorized To Store Dua !');
        }
        $dua = $this->query->saveDua($request);
        if ($dua) {
            alert()->success('Dua', 'Item Created Successfully');
            return redirect()->route('admin.dua.index');
        } else {
            alert()->error('Dua', 'Failed To Create');
            return redirect()->route('admin.dua.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dua  $dua
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('dua-view')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua !');
        }
        $dua = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua  Details',
            'dua' => $dua,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dua  $dua
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View
    {
        if (is_null($this->user) or !$this->user->can('dua-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To View Dua !');
        }
        $dua = $this->query->find($id);
        $data = [
            'moduleName' => 'Dua',
            'duaCategories' => $this->duaCategoryQuery->getActiveData(),
            'dua' => $dua,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dua  $dua
     * @return \Illuminate\Http\Response
     */
    public function update(DuaRequest $request, Dua $dua):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-edit')) {
            abort(403, 'Sorry!! You are Unauthorized To Update Dua !');
        }
        $updateDua = $this->query->updateDua($request, $dua);
        if ($updateDua) {
            alert()->success('Dua Module', 'Item Updated Successfully');
            return redirect()->route('admin.dua.index');
        } else {
            alert()->error('Dua Module', 'Failed To Update');
            return redirect()->route('admin.dua.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-delete')) {
            abort(403, 'Sorry!! You are Unauthorized To Delete Dua !');
        }
        $dua = $this->query->find($id);
        if ($dua->audio) {
//            Storage::delete('/public/dua/audios/' . $dua->audio);
            Storage::disk('s3')->delete('/dua/audios/' . $dua->audio);
        }
        if ($dua->video) {
//            Storage::delete('/public/dua/videos/' . $dua->video);
            Storage::disk('s3')->delete('/dua/videos/' . $dua->video);

        }
        $dua->delete();
        return response()->json(['status' => true, 'data' => $dua]);
    }


    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('dua-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $dua = $this->query->find($id);
        $status = $dua->status == 0 ? 1 : 0;
        $dua->update(['status' => $status]);
        if ($dua) {
            if ($dua->status == 1) {
                alert()->success('Dua Module', 'Item Status Is Active');
            }
            if ($dua->status == 0) {
                alert()->success('Dua Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Dua Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.dua.index');
    }

    public function orderView():View
    {
        $duas =  Dua::orderBy('position','asc')->get();
        return view(self::moduleDirectory . 'order-list', compact('duas'));
    }

    public function reOrderList(Request $request):Response
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        foreach ($request->ids as $index => $id) {
            DB::table('duas')
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
            $filePath = 'dua/videos/' . $fileName;
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
            $filePath = 'dua/audios/' . $fileName;
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
