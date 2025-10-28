<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'type' => $this['type'], // Group key
            'content' => collect($this['content'])->map(function ($content) {
                return [
                    'title' => $content['title'],
                    'description' => $content['description'],
                    'image_path' => asset($content['image_path']),
                ];
            }),
        ];
    }
}
