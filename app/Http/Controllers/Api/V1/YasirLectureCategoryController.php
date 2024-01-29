<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\YasirLectureCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class YasirLectureCategoryController extends Controller
{
    public function getYasirLectureCategoryList(Request $request): JsonResponse
    {
        $arabicLectureId = $request->input('yasir_lecture_id');

        if (!$arabicLectureId) {
            return response()->json(['status' => 'failed', 'message' => 'Yasir Lecture ID is required']);
        }

        $datas = YasirLectureCategory::query()
            ->where('status', 1)
            ->where('yasir_lecture_id',  $arabicLectureId)
            ->orderBy('id', 'asc')
            ->get()
            ->makeHidden('position');

        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'yasir_lecture_id' => $data->yasir_lecture_id,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/Yasir_Qadhi/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/Yasir_Qadhi/audio/' . $data->audio) : null,
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
