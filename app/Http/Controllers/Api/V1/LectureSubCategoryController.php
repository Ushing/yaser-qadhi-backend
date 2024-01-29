<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LectureSubcategoryResource;
use App\Models\LectureCategory;
use App\Models\LectureSubCategory;
use Illuminate\Http\Request;

class LectureSubCategoryController extends Controller
{

    public function index()
    {
        $subcategory = LectureSubCategory::where('status',1)->get();
        $response = LectureSubcategoryResource::collection($subcategory);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

    public function getSubcategoryById($id)
    {
        $subcategory = LectureSubCategory::where('lecture_category_id',$id)->where('status',1)->get();
        //return response()->json($subcategory);
        $response = LectureSubcategoryResource::collection($subcategory);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }


    }


}
