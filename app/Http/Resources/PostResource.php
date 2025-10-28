<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->image ?? null,
                'role' => $this->user->role,
            ],
            'title' => $this->title,
            'body' => $this->body,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'is_admin' => $this->user->role === 'admin',
            'is_pinned' => $this->is_pinned,
            'media' => $this->media,
            'created_at' => $this->created_at,
            'likes_count' => $this->likes->count() ?? 0,
            'comments_count' => $this->comments->count() ?? 0,
            'is_liked_by_user' => $this->isLikedBy(request()->user()),
            'categories' => $this->categories,
        ];
    }
}
