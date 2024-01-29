<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LectureSubcategoryResource extends JsonResource
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
            'id'=> $this->id,
            'name' => $this->name,
            'category' => $this->lecture_category_id,
            'created' => $this->created_at,

        ];
    }
}
