<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TahajjudPrayerList;
use Illuminate\Http\JsonResponse;

class TahajjudPrayerListController extends Controller
{
    public function getTahajjudPrayerList(): JsonResponse
    {
        $datas = TahajjudPrayerList::query()->get();
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/tahajjud_prayers/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/tahajjud_prayers/audio/' . $data->audio) : null,
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
