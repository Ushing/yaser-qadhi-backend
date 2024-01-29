<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuranProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuranProfileController extends Controller
{
    public function getQuranProfileListByCustomerId($id): JsonResponse
    {
        $profileList = QuranProfile::where('customer_id', $id)->get();
        if ($profileList->count() == 0) {
            return response()->json(['success' => true, 'message' => "Profile list empty"], 200);
        } else {
            return response()->json(['success' => true, 'profiles' => $profileList,], 200);
        }
    }

    public function storeQuranProfileInformation(Request $request): JsonResponse
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
            $storeProfile = QuranProfile::create([
                'customer_id' => $request->customer_id,
                'name' => $request->name,
                'age' => $request->age,
                'country' => $request->country,
                'gender' => $request->gender,
            ]);
            if ($storeProfile) {
                $profileInfo = QuranProfile::where('customer_id', $request->customer_id)->get();
                return response()->json(['success' => true, 'profiles' => $profileInfo,], 200);
            } else {
                return response()->json(['success' => false, 'message' => "Failed to create profile"], 400);
            }
        }
    }

    public function updateQuranProfileInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'customer_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'age' => ['required', 'string'],
            'country' => ['required', 'string'],
            'gender' => Rule::in(['male', 'female', 'other']),
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        } else {
            if (QuranProfile::where('id', $request->id)->exists()) {
                $profile = QuranProfile::findOrFail($request->id);
                $updateProfile = $profile->update([
                    'customer_id' => $request->customer_id,
                    'name' => $request->name,
                    'age' => $request->age,
                    'country' => $request->country,
                    'gender' => $request->gender,
                ]);
                if ($updateProfile) {
                    $profileInfo = QuranProfile::where('id', $request->id)->get();
                    return response()->json(['success' => true, 'message' => 'Profile is updated', 'profile' => $profileInfo,], 200);
                }
            } else {
                return response()->json(['success' => false, 'message' => "No profile found"], 400);
            }
        }
    }

    public function deleteQuranProfile($id): JsonResponse
    {
        if (QuranProfile::where('id', $id)->exists()) {
            $profile = QuranProfile::findOrFail($id);
            if ($profile) {
                $deleteProfile = $profile->delete();
                if ($deleteProfile) {
                    return response()->json(['success' => true, 'message' => 'profile is deleted successfully',], 200);
                } else {
                    return response()->json(['success' => false, 'message' => "Failed to delete"], 400);
                }
            }
        }
        return response()->json(['success' => false, 'message' => "No profile found"], 400);
    }
}
