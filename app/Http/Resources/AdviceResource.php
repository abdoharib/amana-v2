<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'guardian' => $this->guardian,
            'guardian_id' => $this->guardian_id,
            'kid' => $this->kid,
            'kid_id' => $this->kid_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}