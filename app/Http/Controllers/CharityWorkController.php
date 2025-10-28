<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharityWorkRequest;
use App\Models\CharityWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharityWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $charityWorks = CharityWork::all();
        return response()->json($charityWorks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CharityWorkRequest $request)
    {
        $validated = $request->validated();
        // Handle file upload if necessary
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('charity_works', 'public');
        }
        $charityWork = CharityWork::create($validated);

        return response()->json([
            'message' => 'Charity work created successfully.',
            'data' => $charityWork,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CharityWork $charityWork)
    {
        return response()->json($charityWork);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CharityWorkRequest $request, CharityWork $charityWork)
    {
     
        $validated = $request->validated();
        // Handle file upload if necessary
        if ($request->hasFile('image')) {
            // Delete old image if necessary
            if ($charityWork->image_path) {
                Storage::disk('public')->delete($charityWork->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('charity_works', 'public');
        }


        $charityWork->update($validated);

        return response()->json([
            'message' => 'Charity work updated successfully.',
            'data' => $charityWork,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CharityWork $charityWork)
    {
        $charityWork->delete();

        return response()->json([
            'message' => 'Charity work deleted successfully.',
        ]);
    }
}