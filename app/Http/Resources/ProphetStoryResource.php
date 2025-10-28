<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProphetStoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_path' => $this->image_path,
            'details' => ProphetStoryDetailResource::collection($this->details), // Load details when they are eager-loaded
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
