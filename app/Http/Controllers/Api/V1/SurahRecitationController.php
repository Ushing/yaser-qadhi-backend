<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurahRecitationFileResource;
use App\Models\ReciteLanguage;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SurahRecitationController extends Controller
{
    public function index(): JsonResponse
    {
        $surahRecitaions = SurahRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get();
        $list = [];
        foreach ($surahRecitaions as $recitaion) {
            $list[] = [
                'id' => $recitaion->id,
                'title' => $recitaion->title,
                'name_arabic' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_arabic,
                'name_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english,
                'name_english_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english_translation,
                'name_bengali_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_bengali_translation,
                'type_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_english,
                'type_bengali' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_bengali,
                'reference_id' => $recitaion->reference_id,
                'audio' => isset($recitaion->audio) ? asset('uploads/surah/audios/' . $recitaion->audio) : null,
                'video' => isset($recitaion->video) ? asset('uploads/surah/videos/' . $recitaion->video) : null,
                'recitation_files' => SurahRecitationFileResource::collection(SurahReciteFile::where('surah_recitation_id', $recitaion->id)->get())
            ];
        }
        if ($list) {
            return response()->json($list, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }



    public function getSurahListByLanguage($langId)
    {
       $surahIds = SurahReciteFile::query()->where('recite_language_id',$langId)->pluck('surah_recitation_id');
       $surahRecitaions = SurahRecitation::query()->whereIn('id',$surahIds)->where('status', 1)->orderBy('surah_id', 'asc')->get();

        $list = [];
        foreach ($surahRecitaions as $recitaion) {
            $list[] = [
                'id' => $recitaion->id,
                'title' => $recitaion->title,
                'name_arabic' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_arabic,
                'name_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english,
                'name_english_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english_translation,
                'name_bengali_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_bengali_translation,
                'type_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_english,
                'type_bengali' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_bengali,
                'reference_id' => $recitaion->reference_id,
                'audio' => isset($recitaion->audio) ? asset('uploads/surah/audios/' . $recitaion->audio) : null,
                'video' => isset($recitaion->video) ? asset('uploads/surah/videos/' . $recitaion->video) : null,
                'recitation_files' => SurahRecitationFileResource::collection(SurahReciteFile::where('recite_language_id', $langId)->where('surah_recitation_id', $recitaion->id)->get())
            ];
        }
        if ($list) {
            return response()->json($list, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }


    public function getSurahRecitationById($id): JsonResponse
    {
        $recitaion = SurahRecitation::query()->where('id', $id)->first();
        $list = [
            'id' => $recitaion->id,
            'title' => $recitaion->title,
            'name_arabic' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_arabic,
            'name_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english,
            'name_english_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_english_translation,
            'name_bengali_translation' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->name_bengali_translation,
            'type_english' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_english,
            'type_bengali' => DB::table('surahs')->where('id', $recitaion->surah_id)->first()->type_bengali,
            'reference_id' => $recitaion->reference_id,
            'audio' => isset($recitaion->audio) ? asset('uploads/surah/audios/' . $recitaion->audio) : null,
            'video' => isset($recitaion->video) ? asset('uploads/surah/videos/' . $recitaion->video) : null,
            'recitation_files' => SurahRecitationFileResource::collection(SurahReciteFile::where('surah_recitation_id', $recitaion->id)->get())
        ];
        if ($list) {
            return response()->json($list, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }

    }

    public function getLanguageList(): JsonResponse
    {
        $list = ReciteLanguage::query()->where('status', 1)->get();

        if ($list->count() > 0) {
            return response()->json($list, 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }
    }
}
