<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HajjChecklist;
use App\Models\HajjProfile;
use App\Models\HajjSublist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HajjController extends Controller
{
    public function getChecklist()
    {
        $list = HajjChecklist::get();
        return response()->json($list);
    }

    public function getSublist($id)
    {
        $sublist = HajjSublist::findOrFail($id);
        return response()->json($sublist);
    }

    public function getHajjProfileListByCustomerId($id): JsonResponse
    {
        $profileList = HajjProfile::where('customer_id', $id)->get();
        if ($profileList) {
            return response()->json(['success' => true, 'profiles' => $profileList,], 200);
        } else {
            return response()->json(['success' => false, 'message' => "Profile list empty"], 400);
        }
    }

    public function storeHajjProfileInformation(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'age' => ['required', 'string'],
            'country' => ['required', 'string'],
            'gender' => Rule::in(['male', 'female', 'other']),
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        } else {
            $storeProfile = HajjProfile::create([
                'customer_id' => $request->customer_id,
                'name' => $request->name,
                'age' => $request->age,
                'country' => $request->country,
                'gender' => $request->gender,
            ]);
            if ($storeProfile) {
                $profileInfo = HajjProfile::where('customer_id', $request->customer_id)->get();
                return response()->json(['success' => true, 'profiles' => $profileInfo,], 200);
            } else {
                return response()->json(['success' => false, 'message' => "Failed to create profile"], 400);
            }
        }
    }
}
