<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\CategoryItemCollection;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function categoryIndex()
    {
        $items = Item::filter()->with('itemDetails')->get(); // Load all items
        return new CategoryItemCollection($items);
    }

    public function index()
    {
        $contents = Item::with('category')->filter()->get();
        return response()->json($contents);
    }


    public function store(StoreItemRequest $request, FileHelper $fileHelper)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        if ($request->hasFile('image')) {
            $filePath = $fileHelper->execute($request->file('image'), 'items');
            $validated['image_path'] =  $filePath;
        }
        // Create the item
        $item = Item::create([
            'title' => $validated['title'],
            'image_path' => $validated['image_path'],
            'category_id' => $validated['category_id'],
            'educational_content_id' => $validated['educational_content_id'],
        ]);

        // Add the details to the item
        foreach ($validated['details'] as $index => $detail) {
            // Save image file for detail
            if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                $detail['image_path'] = $request->file("details.$index.image")->store('item_details/images', 'public');
            }
    
            // Save audio file for detail
            if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {
                $detail['audio_path'] = $request->file("details.$index.audio")->store('item_details/audio', 'public');
            }
    
            $item->itemDetails()->create($detail);
        }

        DB::commit();

        // Return the created item with its details
        return $item->load('itemDetails');
    }
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    public function update(UpdateItemRequest $request, Item $item, FileHelper $fileHelper)
    {

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $filePath = $fileHelper->execute($request->file('image'), 'items');
            $validated['image_path'] =  $filePath;
        }

        // Update the item
        $item->update([
            'title' => $validated['title'] ?? $item->title,
            'image_path' => $validated['image_path'] ?? $item->image_path,
            'category_id' => $validated['category_id'] ?? $item->category_id,
            'educational_content_id' => $validated['educational_content_id'] ?? $item->educational_content_id,
        ]);
      
        if(isset($validated['deletedIds'])){
            ItemDetail::destroy($validated['deletedIds']);
        }

        if (isset($validated['details'])) {
            foreach ($validated['details'] as $index => $detail) {
                if ($detail['id']) {

                    $itemDetail = ItemDetail::findOrFail($detail['id']);

                    // Save updated image file for detail
                    if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                        $detail['image_path'] = $request->file("details.$index.image")->store('item_details/images', 'public');
                    }

                    // Save updated audio file for detail
                    if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {
                        $detail['audio_path'] = $request->file("details.$index.audio")->store('item_details/audio', 'public');
                    }

                    $itemDetail->update($detail);
                } else {
                    // Create new detail with image and audio files
                    if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                        $detail['image_path'] = $request->file("details.$index.image")->store('item_details/images', 'public');
                    }

                    if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {
                        $detail['audio_path'] = $request->file("details.$index.audio")->store('item_details/audio', 'public');
                    }

                    $item->itemDetails()->create($detail);
                }
            }
        }

        // Return the updated item with its details
        return $item->load('itemDetails');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->noContent();
    }
}
