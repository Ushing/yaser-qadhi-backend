<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dua;
use App\Models\Lecture;
use App\Models\Tag;
use App\Models\TagDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class TagDetailController extends Controller
{
    const moduleDirectory = 'admin.tags.';

    //tag create
    public function tagDetails($contentId, $type): View
    {
        $data = [
            'tags' => Tag::query()->orderBy('id', 'asc')->get(),
            'content' => $contentId,
            'contentType' => $type
        ];

        return view(self::moduleDirectory . 'tag_details', $data);

    }

    //tag store
    public function storeTagDetails(Request $request): string|RedirectResponse
    {
        try {
            $existance = TagDetail::whereIn('tag_id', collect($request->tag_ids)->toArray())
                ->where('content_id', $request->content_id)
                ->where('content_type', $request->content_type)->exists();

            $route = 'admin.' . $request->content_type . '.index';
            if ($existance) {
                alert()->error('Tags', 'Tags Already Attached');
                return redirect()->route($route);
            } else {
                foreach ($request->tag_ids as $tagId) {
                    $storeTags = DB::table('tag_details')->insert([
                        [
                            'tag_id' => $tagId,
                            'content_id' => $request->content_id,
                            'content_type' => $request->content_type
                        ]
                    ]);
                }
                if ($storeTags) {
                    alert()->success('Tags', 'Tags Added Successfully');
                    return redirect()->route($route);
                } else {
                    alert()->error('Tags', 'Tags Failed To Attach');
                    return redirect()->route($route);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    //dua tag list
    public function getDuaTagDetails(): Factory|View|Application
    {
        $contentIds= TagDetail::where('content_type','dua')->pluck('content_id')->unique();
        $datas =[];

        foreach ($contentIds as $duaId){
            $duaTitle = Dua::where('id',$duaId)->get()->pluck('title');
            $duaRefId = Dua::where('id',$duaId)->get()->pluck('reference_id');
            $datas[] = [
                'id'=>$duaId,
                'title'=> $duaTitle,
                'reference_id'=> $duaRefId,
                'tagIds'=> TagDetail::where('content_id',$duaId)->pluck('tag_id'),
            ];
        }

        $duaTags = collect($datas)->sortBy('id');
        return view(self::moduleDirectory . 'duas.'.'index', compact('duaTags'));

    }

    //edit dua tag list
    public function editDuaTagDetails($id,$type): Factory|View|Application
    {
        $data = [
            'tags' => Tag::query()->orderBy('id', 'asc')->get(),
            'contentId' => $id,
            'contentType' => $type,
            'selectedTagIds'=>TagDetail::where('content_id',$id)->where('content_type',$type)->pluck('tag_id')->toArray()
        ];

        return view(self::moduleDirectory . 'duas.'.'edit', $data);

    }


    //lecture tag list
    public function getLectureTagDetails(): Factory|View|Application
    {
        $contentIds= TagDetail::where('content_type','lecture')->pluck('content_id')->unique();
        $datas =[];
        foreach ($contentIds as $lectureId){
            $lectureTitle = Lecture::where('id',$lectureId)->get()->pluck('title');
            $lectureRefId = Lecture::where('id',$lectureId)->get()->pluck('reference_id');
            $datas[] = [
                'id'=>$lectureId,
                'title'=> $lectureTitle,
                'reference_id'=> $lectureRefId,
                'tagIds'=> TagDetail::where('content_id',$lectureId)->pluck('tag_id'),
            ];
        }
        $lectureTags = collect($datas)->sortBy('id');
        return view(self::moduleDirectory . 'lectures.'.'index', compact('lectureTags'));

    }

    //edit lecture tag list
    public function editLectureTagDetails($id,$type): Factory|View|Application
    {
        $data = [
            'tags' => Tag::query()->orderBy('id', 'asc')->get(),
            'contentId' => $id,
            'contentType' => $type,
            'selectedTagIds'=>TagDetail::where('content_id',$id)->where('content_type',$type)->pluck('tag_id')->toArray()
        ];

        return view(self::moduleDirectory . 'lectures.'.'edit', $data);

    }


    //update tags
    public function updateTagDetails(Request $request): RedirectResponse
    {
      $trashIds = TagDetail::where('content_id',$request->content_id)->where('content_type',$request->content_type)->pluck('id')->toArray();
      if ($trashIds){
          DB::table('tag_details')->whereIn('id', $trashIds)->delete();
      }
        foreach ($request->tag_ids as $tagId) {
            $updateTags = DB::table('tag_details')->insert([
                [
                    'tag_id' => $tagId,
                    'content_id' => $request->content_id,
                    'content_type' => $request->content_type
                ]
            ]);
        }
        $route = 'admin.tagDetails.' . $request->content_type;
        if ($updateTags) {
            alert()->success('Tags', 'Tags Updated Successfully');
            return redirect()->route($route);
        } else {
            alert()->error('Tags', 'Tags Failed To Update');
            return redirect()->route($route);
        }
    }


}
