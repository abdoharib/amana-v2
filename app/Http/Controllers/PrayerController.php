<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrayerRequest;
use App\Http\Resources\PrayerResource;
use App\Models\Prayer;
use App\Models\PrayerContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrayerController extends Controller
{
    public function index()
    {
        return response()->json(Prayer::all());
    }

    public function store(PrayerRequest $request)
    {
       
        $validated = $request->validated();

        $prayer = Prayer::create([
            'title' => $validated['title'],
            'image_path' => $request->file('image')->store('prayer_images', 'public'),
        ]);

        return response()->json($prayer);
    }

    public function show(Prayer $prayer)
    {
        return response()->json($prayer->load('contents'));
    }

    public function update(PrayerRequest $request, Prayer $prayer)
    {
        $validated = $request->validated();
        $prayer->update([
            'title' =>$validated['title'],
            'image_path' => $request->hasFile('image')
                ? $request->file('image')->store('prayer_images', 'public')
                : $prayer->image_path,
        ]);

        return response()->json($prayer);
    }

    public function destroy(Prayer $prayer)
    {
        $prayer->contents()->delete();
        $prayer->delete();

        return response()->json(['message' => 'Prayer deleted successfully']);
    }
}
