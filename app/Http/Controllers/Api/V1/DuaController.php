<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DuaResource;
use App\Models\Dua;
use Illuminate\Http\Request;

class DuaController extends Controller
{


    public function getDuaBySubcategoryId($subcategory)
    {
        $dua = Dua::where('dua_sub_category_id', $subcategory)->where('status', 1)->orderBy('position','asc')->get();
        $response = DuaResource::collection($dua);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);

        }
    }
    public function countDuaBySubcategoryId($subcategory)
    {
        $dua = Dua::where('dua_sub_category_id', $subcategory)->where('status', 1)->orderBy('position','asc')->count();
       // $response = DuaResource::collection($dua);
        if ($dua) {
            return response()->json($dua, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);

        }
    }

    public function getLatestDua()
    {
        $dua = Dua::where('status',1)->orderBy('created_at','desc')->take(20)->get();
        $response = DuaResource::collection($dua);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

}
