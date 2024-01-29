<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ArabicGrammar;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArabicGrammarController extends Controller
{
    public function getArabicGrammarList(): JsonResponse
    {
        $datas = ArabicGrammar::query()->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'icon_image' => isset($data->icon_image) ? asset('images/' . $data->icon_image) : null,
                    'cover_image' => isset($data->cover_image) ? asset('images/' . $data->cover_image) : null,
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
