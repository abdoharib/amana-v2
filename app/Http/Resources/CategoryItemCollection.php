<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->groupBy('category_id')->map(function ($items, $categoryId) {
            $category = Category::find($categoryId); // Fetch category details
            return [
                'category' => $category ? $category->title : 'Unknown Category',
                'items' => ItemResource::collection($items),
            ];
        })->values();
    }
}
