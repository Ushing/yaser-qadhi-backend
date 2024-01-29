<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function getArabicEnglishList()
    {
        $quran = DB::table('surahs')->select('id','surah_number as number','name_arabic as name','name_english as surahName','name_english_translation as translation','type_english as revelationType')->get();
        $data = [
            'code'=>200,
            'status'=>'Ok',
            'data'=>$quran,
        ];
        return response()->json($data);
    }
    public function getArabicBanglaList()
    {
        $quran = DB::table('surahs')->select('id','surah_number as number','name_arabic as name','name_bengali as surahName','name_bengali_translation as translation','type_bengali as revelationType')->get();
        $data = [
            'code'=>200,
            'status'=>'Ok',
            'data'=>$quran,
        ];
        return response()->json($data);
    }
    public function getArabicEnglishDetails($id)
    {
        $quran = DB::table('ayahs')
                ->join('surahs', 'ayahs.surah_id', '=', 'surahs.id')
                ->join('ayah_edition_english', 'ayahs.id', '=', 'ayah_edition_english.ayah_id')
              //  ->select('ayahs.*, ayah_edition_english.translation')
                ->where('surah_id',$id)->get();

        return response()->json(
            ['result'=>$quran]
        );
    }
    public function getArabicBanglaDetails($id)
    {
        $quran = DB::table('ayahs')
                ->join('surahs', 'ayahs.surah_id', '=', 'surahs.id')
                ->join('ayah_edition_bengali', 'ayahs.id', '=', 'ayah_edition_bengali.ayah_id')
              //  ->select('ayahs.*, ayah_edition_english.translation')
                ->where('surah_id',$id)->get();

        return response()->json(
            ['result'=>$quran]
        );
    }
}
