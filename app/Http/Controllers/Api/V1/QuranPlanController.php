<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuranPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuranPlanController extends Controller
{
    public function storePlans(Request $request): JsonResponse
    {
        $data = QuranPlan::create([
            'customer_id' => $request->customer_id,
            'type_id' => $request->type_id,
            'title' => $request->title,
            'value' => $request->value,
        ]);
        if ($data) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false, 'message' => "Failed to create plan."], 400);
        }
    }

    public function getAllPlans(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $type_id = $request->input('type_id');

        $data = QuranPlan::where('customer_id', $customer_id)
            ->where('type_id', $type_id)
            ->get();

        return response()->json($data);
    }

    public function deleteMyQuranPlan($id): JsonResponse
    {
        if (QuranPlan::where('id', $id)->exists()) {
            $data = QuranPlan::findOrFail($id);
            if ($data) {
                $deleteProfile = $data->delete();
                if ($deleteProfile) {
                    return response()->json(['success' => true, 'message' => 'This plan is deleted successfully.',], 200);
                } else {
                    return response()->json(['success' => false, 'message' => "Failed to delete."], 400);
                }
            }
        }
        return response()->json(['success' => false, 'message' => "No data found."], 400);
    }
}
