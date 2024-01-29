<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurahRecitationFileResource;
use App\Models\QuranStatus;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuranStatusController extends Controller
{
    public function storeTasks(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', 'integer'],
            'profile_id' => ['required', 'integer'],
            'type_id' => ['required', 'integer'],
            'surah_no' => ['required', 'integer'],
            'ayah_start' => ['required', 'integer'],
            'ayah_end' => ['required', 'integer'],
            'entry_date' => ['required'],
            'entry_time' => ['required'],
            'isChecked' => ['required'],
            'subtitle_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        } else {

            if ($request->isChecked == 'true') {
                $checked = 1;
            } else {
                $checked = 0;
            }
            $storeStatus = QuranStatus::create([
                'customer_id' => $request->customer_id,
                'profile_id' => $request->profile_id,
                'surah_no' => $request->surah_no,
                'entry_date' => $request->entry_date,
                'entry_time' => $request->entry_time,
                'ayah_start' => $request->ayah_start,
                'ayah_end' => $request->ayah_end,
                'isChecked' => $checked,
                'execution_date' => $request->execution_date,
                'execution_time' => $request->execution_time,
                'isExecuted' => false,
                'type_id' => $request->type_id,
                'subtitle_id' => $request->subtitle_id,
                'edition_id' => $request->edition_id,
            ]);
            if ($storeStatus) {
                //$statusInfo = QuranStatus::where('customer_id', $request->customer_id)->get();
                return response()->json(['success' => true, 'message' => "Successfully  created."], 200);
            } else {
                return response()->json(['success' => false, 'message' => "Failed to create."], 400);
            }
        }


    }

    public function updateTasks(Request $request, $id)
    {
        $tasks = QuranStatus::findOrFail($id);
        if ($request->isExecuted) {
            $tasks->isExecuted = $request->isExecuted;
        }
        if ($request->execution_date) {
            $tasks->execution_date = $request->execution_date;
        }
        if ($request->execution_time) {
            $tasks->execution_time = $request->execution_time;
        }

        $tasks->save();
        return response()->json(
            [
                'message' => 'Successfully updated.',
                'status' => 200
            ]
        );
    }

    //Executable APIs
    public function getAllExecutableTasks($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $data = QuranStatus::where('customer_id', $customer_id)
            ->where('profile_id', $profile_id)
            ->where('type_id', $type_id)
            ->where('isExecuted', 0)
            ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
            ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
            ->join('ayahs', function ($join) {
                $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                    ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
            })
            ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
            ->get();

        $combinedData = $this->combinedResult($data);

        return response()->json($combinedData);
    }

    public function getExecutableTasksByDate($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $date = $request->input('date');
        if ($date != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->where('isExecuted', 0)
                ->where('entry_date', $date)
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);
            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        if ($date_from != null && $date_to != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->where('isExecuted', 0)
                ->whereBetween('entry_date', [$date_from, $date_to])
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);
            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        return response()->json([]);
    }

    public function getExecutableTasksByTime($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $time_from = $request->input('time_from');
        $time_to = $request->input('time_to');
        $date = $request->input('date');

        if ($time_from != null && $time_to != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->where('isExecuted', 0)
                ->where('entry_date', $date)
                ->whereBetween('entry_time', [$time_from, $time_to])
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);

            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        return response()->json([]);
    }

    //Tracking APIs
    public function getAllTasks($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $data = QuranStatus::where('customer_id', $customer_id)
            ->where('profile_id', $profile_id)
            ->where('type_id', $type_id)
            ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
            ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
            ->join('ayahs', function ($join) {
                $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                    ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
            })
            ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
            ->get();

        $combinedData = $this->combinedResult($data);
        return response()->json($combinedData);
    }

    public function getTasksByDate($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $date = $request->input('date');
        if ($date != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->where('entry_date', $date)
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);
            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        if ($date_from != null && $date_to != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->whereBetween('entry_date', [$date_from, $date_to])
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);
            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        return response()->json([]);
    }

    public function getTasksByTime($customer_id, $profile_id, Request $request)
    {
        $type_id = $request->input('type_id');
        $time_from = $request->input('time_from');
        $time_to = $request->input('time_to');
        $date = $request->input('date');

        if ($time_from != null && $time_to != null) {
            $data = QuranStatus::where('customer_id', $customer_id)
                ->where('profile_id', $profile_id)
                ->where('type_id', $type_id)
                ->where('entry_date', $date)
                ->whereBetween('entry_time', [$time_from, $time_to])
                ->join('surahs', 'surahs.surah_number', '=', 'quran_statuses.surah_no')
                ->join('surah_translations', 'surah_translations.surah_id', '=', 'quran_statuses.surah_no')
                ->join('ayahs', function ($join) {
                    $join->on('quran_statuses.surah_no', '=', 'ayahs.surah_id')
                        ->whereRaw('ayahs.number_in_surah BETWEEN quran_statuses.ayah_start AND quran_statuses.ayah_end');
                })
                ->select('quran_statuses.*', 'ayahs.ayah', 'ayahs.ayah_number', 'ayahs.number_in_surah', 'surahs.name_arabic as surah_name', 'surah_translations.name', 'surah_translations.translation', 'surah_translations.type')
                ->get();

            $combinedData = $this->combinedResult($data);
            $response = $this->wrapNewValuesWithSubtitleInformation($combinedData);
            return response()->json($response);
        }
        return response()->json([]);
    }

    function combinedResult($data)
    {
        $combinedData = [];
        foreach ($data as $item) {
            $existingItem = collect($combinedData)->firstWhere('id', $item->id);
            if ($existingItem) {
                $existingItem->ayah[] = $item->ayah;
                $existingItem->ayah_number[] = $item->ayah_number;
                $existingItem->number_in_surah[] = $item->number_in_surah;
            } else {
                $combinedItem = $item->toArray();
                $combinedItem['ayah'] = [$item->ayah];
                $combinedItem['ayah_number'] = [$item->ayah_number];
                $combinedItem['number_in_surah'] = [$item->number_in_surah];
                $combinedData[] = (object)$combinedItem;
            }
        }

        $result = [];
        foreach ($combinedData as $item) {
            $item->ayah = array_values($item->ayah);
            $item->ayah_number = array_values($item->ayah_number);
            $item->number_in_surah = array_values($item->number_in_surah);
            $result[] = $item;
        }

        return $result;
    }


    public function wrapNewValuesWithSubtitleInformation($results):Collection
    {
        $data = [];
        foreach ($results as $res) {
            $data[] = [
                "id" => $res->id,
                "customer_id" => $res->customer_id,
                "profile_id" => $res->profile_id,
                "type_id" => $res->type_id,
                "surah_no" => $res->surah_no,
                "entry_date" => $res->entry_date,
                "entry_time" => $res->entry_time,
                "ayah_start" => $res->ayah_start,
                "ayah_end" => $res->ayah_end,
                "isChecked" => $res->isChecked,
                "execution_date" => $res->execution_date,
                "execution_time" => $res->execution_time,
                "isExecuted" => $res->isExecuted,
                "subtitle_id" => $res->subtitle_id,
                "edition_id" => $res->edition_id,
                "created_at" => $res->created_at,
                "updated_at" => $res->updated_at,
                "ayah" => $res->ayah,
                "ayah_number" => $res->ayah_number,
                "number_in_surah" => $res->number_in_surah,
                "surah_name" => $res->surah_name,
                "name" => $res->name,
                "translation" => $res->translation,
                "type" => $res->type,
                "audio" => isset(SurahRecitation::where('surah_id', $res->surah_no)->first()->audio) ? asset('uploads/surah/audios/' . SurahRecitation::where('surah_id', $res->surah_no)->first()->audio) : null,
                "video" => isset(SurahRecitation::where('surah_id', $res->surah_no)->first()->video) ? asset('uploads/surah/videos/' . SurahRecitation::where('surah_id', $res->surah_no)->first()->video) : null,
                'recitation_files' => SurahRecitationFileResource::collection(SurahReciteFile::where('id', $res->subtitle_id)->get())

            ];
        }
        return collect($data);

    }

}
