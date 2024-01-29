<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LectureResource;
use App\Models\Lecture;
use App\Models\LectureCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LectureController extends Controller
{


    public function getLectureBySubcategoryId($id)
    {
        $lecture = Lecture::where('lecture_sub_category_id', $id)->where('status',1)->get();
        $response = LectureResource::collection($lecture);

        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

    public function getLatestLecture()
    {
        $lecture = Lecture::where('status',1)->orderBy('created_at','desc')->take(20)->get();
        $response = LectureResource::collection($lecture);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

    public function getLatestLectureBySubcategoryId($id)
    {
        $date = \Carbon\Carbon::today()->subDays(7);
        $lecture = DB::table('lectures')
                    ->join('lecture_sub_categories', 'lectures.lecture_sub_category_id', '=', 'lecture_sub_categories.id')
                    ->join('lecture_categories', 'lecture_sub_categories.lecture_category_id', '=', 'lecture_categories.id')
                    ->select('lectures.*')
                    ->where('lecture_categories.id', $id)->where('lectures.status',1)
                    ->where('lectures.created_at','>=',$date)->get();

        $response = LectureResource::collection($lecture);
        //$response = $lecture;

        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
       // return DD($lecture, $date);
    }

}
