<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProphetStoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        return $this->collection->map(function ($story) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'image_path' => $story->image_path,
                'created_at' => $story->created_at,
            ];
        })->toArray();
    }
}
