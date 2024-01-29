<?php

namespace App\Http\Resources;

use App\Models\Language;
use App\Models\ReciteLanguage;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SurahRecitationFileResource extends JsonResource
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
            'recite_language_id' => ReciteLanguage::where('id',$this->recite_language_id)->first()->title,
            'surah_recitation_id' => $this->surah_recitation_id,
            'sub_title_file' => isset($this->sub_title_file) ?  asset('uploads/surah/srtFile/'.$this->sub_title_file) : null,
            'translation' => strip_tags($this->translation),
            'transliteration' => strip_tags($this->transliteration),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
