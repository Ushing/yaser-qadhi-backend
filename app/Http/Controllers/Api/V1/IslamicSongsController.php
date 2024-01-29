<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\IslamicSong;
use App\Models\QuranOneMinuteShort;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IslamicSongsController extends Controller
{
    public function getList():JsonResponse
    {
        $datas = IslamicSong::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/islamicSongs/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/islamicSongs/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => []
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }


    public function getSongById($id):JsonResponse
    {
        $data = IslamicSong::query()->where('id', $id)->first();
        if ($data){
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/islamicSongs/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/islamicSongs/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => [],
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        }else{
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }
}
