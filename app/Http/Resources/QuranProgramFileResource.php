<?php

namespace App\Http\Resources;

use App\Models\QuranProgramList;
use App\Models\ReciteLanguage;
use Illuminate\Http\Resources\Json\JsonResource;

class QuranProgramFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quran_program' => QuranProgramList::where('id',$this->quran_program_list_id)->first()->title,
            'sub_title_file' => isset($this->sub_title_file) ?  asset('uploads/quranProgram/srtFile/'.$this->sub_title_file) : null,
            'translation' => strip_tags($this->translation),
            'transliteration' => strip_tags($this->transliteration),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
