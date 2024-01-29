<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DonationType;
use Illuminate\Http\Request;

class DonationController extends Controller
{
public function index()
{
    $dtype = DonationType::where('status',1)->get();
    return response()->json($dtype);
}
}
