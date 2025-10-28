<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemDetail;
use Illuminate\Support\Facades\Storage;

class ItemDetailController extends Controller
{
    public function index()
    {
        $items = ItemDetail::all();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio' => 'nullable',
            'image' => 'required|file|mimes:jpeg,png,jpg',
            'item_id' => 'required|integer|exists:items,id',
        ]);

        $audioPath = $request->file('audio') ? $request->file('audio')->store('item_details/audio', 'public') : null;
        $imagePath = $request->file('image') ? $request->file('image')->store('item_details/images', 'public') : null;

        $item = ItemDetail::create([
            'title' => $request->title,
            'description' => $request->description,
            'audio_path' => $audioPath,
            'image_path' => $imagePath,
            'item_id' => $request->item_id,
        ]);

        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = ItemDetail::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = ItemDetail::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($item->audio_path);
            $item->audio_path = $request->file('audio')->store('item_details/audio', 'public');
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image_path);
            $item->image_path = $request->file('image')->store('item_details/images', 'public');
        }

        $item->update($request->only(['title', 'description', 'item_id']));
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ItemDetail::findOrFail($id);
        Storage::disk('public')->delete([$item->audio_path, $item->image_path]);
        $item->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
