<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ArabicGrammerCategoryList;
use App\Models\ArabicGrammar;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArabicGrammarCategoryListController extends Controller
{
    public function getArabicGrammerCategoryList(Request $request): JsonResponse
    {
        $arabicGrammarId = $request->input('arabic_grammar_id');

        // Validate if the arabic_grammar_id is provided
        if (!$arabicGrammarId) {
            return response()->json(['status' => 'failed', 'message' => 'Arabic Grammar ID is required']);
        }

        $datas = ArabicGrammerCategoryList::query()
            ->where('status', 1)
            ->where('arabic_grammar_id', $arabicGrammarId)
            ->orderBy('id', 'asc')
            ->get()
            ->makeHidden('position');

        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'arabic_grammar_id' => $data->arabic_grammar_id,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/arabicGrammerCategoryList/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/arabicGrammerCategoryList/audio/' . $data->audio) : null,
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
