<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IslamicSongRequest;
use App\Models\IslamicSong;
use App\Query\IslamicSongQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\Response;


class IslamicSongsController extends Controller
{
    protected IslamicSongQuery $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.islamic-songs.';

    public function __construct(IslamicSongQuery $islamicSongQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/islamic_songs';
        $this->query = $islamicSongQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Islamic Song',
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
            'moduleName' => 'Islamic Song Create',
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(IslamicSongRequest $request): RedirectResponse
    {
        $islamicSong = $this->query->saveIslamicSong($request);
        if ($islamicSong) {
            alert()->success('Islamic Song', 'Item Created Successfully');
            return redirect()->route('admin.islamic_songs.index');
        } else {
            alert()->error('Islamic Song', 'Failed To Create');
            return redirect()->route('admin.islamic_songs.index');
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
        $islamicSong = $this->query->find($id);
        $data = [
            'moduleName' => 'Islamic Song Details',
            'islamicSong' => $islamicSong,
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
        $islamicSong = $this->query->find($id);
        $data = [
            'moduleName' => 'Islamic Song Edit',
            'islamicSong' => $islamicSong,
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
    public function update(IslamicSongRequest $request, IslamicSong $islamicSong):RedirectResponse
    {
        $islamicSong = $this->query->updateIslamicSong($request, $islamicSong);
        if ($islamicSong) {
            alert()->success('Islamic Song', 'Item Updated Successfully');
            return redirect()->route('admin.islamic_songs.index');
        } else {
            alert()->error('Islamic Song', 'Failed To Update');
            return redirect()->route('admin.islamic_songs.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $islamicSong = $this->query->find($id);
        $islamicSong->islamicSongFiles()->delete();
        $islamicSong->delete();
        return response()->json(['status' => true, 'data' => $islamicSong]);
    }


    public function statusChange($id): RedirectResponse
    {
        $islamicSong = $this->query->find($id);
        $status = $islamicSong->status == 0 ? 1 : 0;
        $islamicSong->update(['status' => $status]);
        if ($islamicSong) {
            if ($islamicSong->status == 1) {
                alert()->success('Islamic Song Module', 'Item Status Is Active');
            }
            if ($islamicSong->status == 0) {
                alert()->success('Islamic Song Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Islamic Song Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.islamic_songs.index');
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
            if (!file_exists('uploads/islamicSongs/audios')){
                mkdir('uploads/islamicSongs/audios', 0777, true);
            }
            $file->move('uploads/islamicSongs/audios', $fileName);
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
            if (!file_exists('uploads/islamicSongs/videos')){
                mkdir('uploads/islamicSongs/videos', 0777, true);
            }
            $file->move('uploads/islamicSongs/videos', $fileName);
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
