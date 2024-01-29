<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuranProgramFileResource;
use App\Models\QuranInterview;
use App\Models\QuranOneMinuteShort;
use App\Models\QuranProgramCategory;
use App\Models\QuranProgramFiles;
use App\Models\QuranProgramList;
use Illuminate\Http\JsonResponse;

class QuranProgramController extends Controller
{
    public function getProgramCategory(): JsonResponse
    {
        $data = QuranProgramCategory::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($data->count() > 0) {
            return response()->json($data, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

    public function getQuranProgramList(): JsonResponse
    {
        $datas = QuranProgramList::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'quran_program_category_id' => $data->quran_program_category_id,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranProgram/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranProgram/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => QuranProgramFileResource::collection(QuranProgramFiles::where('quran_program_list_id', $data->id)->get()),
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }

    public function getQuranProgramById($id):JsonResponse
    {
        $datas = QuranProgramList::query()->where('quran_program_category_id', $id)->get();

        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'quran_program_category_id' => $data->quran_program_category_id,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranProgram/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranProgram/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => QuranProgramFileResource::collection(QuranProgramFiles::where('quran_program_list_id', $data->id)->get()),
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }


    public function getQuranInterviewList():JsonResponse
    {
        $datas = QuranInterview::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranInterview/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranInterview/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => []
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }


    public function getQuranInterviewById($id):JsonResponse
    {
        $data = QuranInterview::query()->where('id', $id)->first();
        if ($data){
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranInterview/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranInterview/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => [],
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        }else{
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }



    public function getQuranShortList():JsonResponse
    {
        $datas = QuranOneMinuteShort::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranShorts/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranShorts/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => []
                ];
            }
            return response()->json($response, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }


    public function getQuranShortById($id):JsonResponse
    {
        $data = QuranOneMinuteShort::query()->where('id', $id)->first();
        if ($data){
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'audio' => isset($data->audio) ? asset('uploads/quranShorts/audios/' . $data->audio) : null,
                    'video' => isset($data->video) ? asset('uploads/quranShorts/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'subtitle_files' => [],
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        }else{
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


}
