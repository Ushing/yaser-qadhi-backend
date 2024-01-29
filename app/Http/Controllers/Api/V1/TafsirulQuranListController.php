<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TafsirulQuranList;
use Illuminate\Http\JsonResponse;

class TafsirulQuranListController extends Controller
{
    public function getTafsirulQuranList(): JsonResponse
    {
        $datas = TafsirulQuranList::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/TafsirulQuran/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/TafsirulQuran/audio/' . $data->audio) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }
}
