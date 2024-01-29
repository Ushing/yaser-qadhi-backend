<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DuaCategory;
use Illuminate\Http\Request;

class DuaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dua_category = DuaCategory::where('status',1)->get();
        return response()->json($dua_category, 200);
    }


}
