<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DuaSubCategory;
use Illuminate\Http\Request;

class DuaSubCategoryController extends Controller
{

    public function index()
    {
        $subcat = DuaSubCategory::where('status', 1)->get();
        return response()->json($subcat, 200);
    }

    public function getSubcategoryById($category)
    {
        $subcategory = DuaSubCategory::where('dua_category_id',$category)->where('status',1)->get();
        return response()->json($subcategory, 200);
    }


}
