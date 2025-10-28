<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoralValueRequest;
use App\Models\MoralValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MoralValueController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $charityWorks = MoralValue::all();
        return response()->json($charityWorks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MoralValueRequest $request)
    {
        $validated = $request->validated();
        // Handle file upload if necessary
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('charity_works', 'public');
        }
        $charityWork = MoralValue::create($validated);

        return response()->json([
            'message' => 'Charity work created successfully.',
            'data' => $charityWork,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MoralValue $moralValue)
    {
        return response()->json($moralValue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MoralValueRequest $request, MoralValue $moralValue)
    {
     
        $validated = $request->validated();
        // Handle file upload if necessary
        if ($request->hasFile('image')) {
            // Delete old image if necessary
            if ($moralValue->image_path) {
                Storage::disk('public')->delete($moralValue->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('moral_values', 'public');
        }


        $moralValue->update($validated);

        return response()->json([
            'message' => 'Charity work updated successfully.',
            'data' => $moralValue,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MoralValue $moralValue)
    {
        // Delete the image from storage if it exists
        if ($moralValue->image_path) {
            Storage::disk('public')->delete($moralValue->image_path);
        }

        // Delete the charity work record
        $moralValue->delete();

        return response()->json([
            'message' => 'Charity work deleted successfully.',
        ]);
    }
}
