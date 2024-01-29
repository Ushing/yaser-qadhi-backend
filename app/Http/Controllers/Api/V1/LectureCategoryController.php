<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LectureCategoryResource;
use App\Models\LectureCategory;
use Illuminate\Http\Request;

class LectureCategoryController extends Controller
{

    public function index()
    {
        $category = LectureCategory::where('status',1)->get();
        $response = LectureCategoryResource::collection($category);

       // return response()->json($response, 200);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);


        }
    }


}
