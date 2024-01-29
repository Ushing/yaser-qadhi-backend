<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DuaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'audio' => isset($this->audio) ? Storage::disk('s3')->url('dua/audios/'.$this->audio) : null,
            'video' => isset($this->video) ? Storage::disk('s3')->url('dua/videos/'.$this->video) : null,
            'translation' => strip_tags($this->translation),
            'transliteration' => strip_tags($this->transliteration),
            'arabic_dua' => strip_tags($this->arabic_dua),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
