<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\MessageBanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banner = MessageBanner::where('status',1)->get();
        $response = BannerResource::collection($banner);
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }
}
