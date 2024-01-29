<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\JannahAndJahannamCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JannahJahannamCategoryController extends Controller
{
    public function getJannahJahannamCategoryList(Request $request): JsonResponse
    {
        $arabicGrammarId = $request->input('jannah_and_jahannam_id');

        // Validate if the arabic_grammar_id is provided
        if (!$arabicGrammarId) {
            return response()->json(['status' => 'failed', 'message' => 'Jannah and Jahannam ID is required']);
        }

        $datas = JannahAndJahannamCategory::query()
            ->where('status', 1)
            ->where('jannah_and_jahannam_id', $arabicGrammarId)
            ->orderBy('id', 'asc')
            ->get()
            ->makeHidden('position');

        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'jannah_and_jahannam_id' => $data->jannah_and_jahannam_id,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/jannahJahannamCategory/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/jannahJahannamCategory/audio/' . $data->audio) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }
}
