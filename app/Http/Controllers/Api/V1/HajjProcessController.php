<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HajjProcess;
use Illuminate\Http\Request;

class HajjProcessController extends Controller
{
    public function index()
    {
        $hprocess = HajjProcess::all();
        return response()->json($hprocess,200);
    }
}
