<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DuaElsabag;
use App\Models\HamdElsabag;
use App\Models\KhatiraLectureRecitation;
use App\Models\KhudbahLectureRecitation;
use App\Models\QuranRecitation;
use App\Models\QuranSlowRecitation;
use App\Models\RegularPrayerRecitation;
use App\Models\TarawihPrayerRecitation;
use App\Models\KhutbahList;
use Illuminate\Http\JsonResponse;

class RecitationController extends Controller
{
    public function getQuranList(): JsonResponse
    {
        $datas = QuranRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/quranRecitations/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/quranRecitations/audio/' . $data->audio) : null,
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

    public function getKhutbahList(): JsonResponse
{
    $datas = KhutbahList::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
    if ($datas->count() > 0) {
        $response = [];
        foreach ($datas as $data) {
            $response[] = [
                'id' => $data->id,
                'title' => $data->title,
                'reference_id' => $data->reference_id,
                'video' => isset($data->video) ? url('uploads/khutbahList/videos/' . $data->video) : null,
                'audio' => isset($data->audio) ? url('uploads/khutbahList/audio/' . $data->audio) : null,
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


    public function getQuranById($id): JsonResponse
    {
        $data = QuranRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/quranRecitations/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }



    public function getSlowQuranList(): JsonResponse
    {
        $datas = QuranSlowRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/quranSlowRecitations/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/quranSlowRecitations/audio/' . $data->audio) : null,
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


    public function getSlowQuranById($id): JsonResponse
    {
        $data = QuranSlowRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/quranSlowRecitations/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


    public function getTarawihList(): JsonResponse
    {
        $datas = TarawihPrayerRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/tarawihRecitations/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/tarawihRecitations/audio/' . $data->audio) : null,
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

    public function getTarawihById($id): JsonResponse
    {
        $data = TarawihPrayerRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/tarawihRecitations/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


    public function getRegularList(): JsonResponse
    {
        $datas = RegularPrayerRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/regularRecitations/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/regularRecitations/audio/' . $data->audio) : null,
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

    public function getRegularById($id): JsonResponse
    {
        $data = RegularPrayerRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/regularRecitations/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


    public function getKhudbahList(): JsonResponse
    {
        $datas = KhudbahLectureRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/khudbahLectures/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/khudbahLectures/audio/' . $data->audio) : null,
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

    public function getKhudbahById($id): JsonResponse
    {
        $data = KhudbahLectureRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/khudbahLectures/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


    public function getKhatiraList(): JsonResponse
    {
        $datas = KhatiraLectureRecitation::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/khatiraLectures/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/khatiraLectures/audio/' . $data->audio) : null,
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

    public function getKhatiraById($id): JsonResponse
    {
        $data = KhatiraLectureRecitation::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/khatiraLectures/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }



    public function getDuaList(): JsonResponse
{
    $datas = DuaElsabag::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');

    if ($datas->count() > 0) {
        $response = [];

        foreach ($datas as $data) {
            $response[] = [
                'id' => $data->id,
                'title' => $data->title,
                'reference_id' => $data->reference_id,
                'video' => isset($data->video) ? asset('uploads/duaElsabags/videos/' . $data->video) : null,
                'audio' => isset($data->audio) ? asset('uploads/duaElsabags/audio/' . $data->audio) : null,
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


    public function getDuaById($id): JsonResponse
    {
        $data = DuaElsabag::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/duaElsabags/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }


    public function getHamdList(): JsonResponse
    {
        $datas = HamdElsabag::query()->where('status', 1)->orderBy('id', 'asc')->get()->makeHidden('position');
        if ($datas->count() > 0) {
            $response = [];
            foreach ($datas as $data) {
                $response[] = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/hamdElsabags/videos/' . $data->video) : null,
                    'audio' => isset($data->audio) ? asset('uploads/hamdElsabags/audio/' . $data->audio) : null,
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

    public function getHamdById($id): JsonResponse
    {
        $data = HamdElsabag::query()->where('id', $id)->first();
        if ($data) {
            if ($data->count() > 0) {
                $list = [
                    'id' => $data->id,
                    'title' => $data->title,
                    'reference_id' => $data->reference_id,
                    'video' => isset($data->video) ? asset('uploads/hamdElsabags/videos/' . $data->video) : null,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
                return response()->json($list, 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'List Empty']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No Data Found']);
        }
    }

}
