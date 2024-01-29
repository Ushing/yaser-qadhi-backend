<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LectureResource extends JsonResource
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
            'title' =>$this->title,
            'audio' => isset($this->audio) ? Storage::disk('s3')->url('lecture/audios/'.$this->audio) : null,
            'video' => isset($this->video) ? Storage::disk('s3')->url('lecture/videos/'.$this->video) : null,
            'description' => strip_tags($this->description),
            'category' => $this-> lecture_sub_category_id,
            'created' => $this-> created_at,

        ];
    }
}
