<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DuaResource;
use App\Http\Resources\LectureResource;
use App\Models\Dua;
use App\Models\Lecture;
use App\Models\Tag;
use App\Models\TagDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class TagController extends Controller
{
    public function getAllTag()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }


    public function getDuaContentByTagId($id):JsonResponse
    {
        $tags =  TagDetail::where('tag_id',$id)->where('content_type','dua')->get();
       $data = [];
       foreach ($tags as $tag){
               $data[]=[
                   'tag_id'=>$tag->tag_id,
                   'tag_title'=> Tag::where('id',$tag->tag_id)->first()->name,
                   'dua'=>  DuaResource::collection(Dua::where('id',$tag->content_id)->get()),
               ];
       }
       if ($data){
           return response()->json($data,200);
       }else{
           return response()->json(['status' => 'failed', 'message' => 'List Empty']);
       }

    }

    public function getLectureContentByTagId($id):JsonResponse
    {
        $tags =  TagDetail::where('tag_id',$id)->where('content_type','lecture')->get();
        $data = [];
        foreach ($tags as $tag){
            $data[]=[
                'tag_id'=>$tag->tag_id,
                'tag_title'=> Tag::where('id',$tag->tag_id)->first()->name,
                'lecture'=>  LectureResource::collection(Lecture::where('id',$tag->content_id)->get()),
            ];
        }
        if ($data){
            return response()->json($data,200);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'List Empty']);
        }

    }

}
