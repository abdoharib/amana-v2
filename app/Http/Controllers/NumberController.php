<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberRequest;
use App\Models\Number;
use Illuminate\Support\Facades\Storage;

class NumberController extends Controller
{
    /**
     * Display a listing of the numbers.
     */
    public function index()
    {
        $numbers = Number::all();

        return response()->json([
            'success' => true,
            'data' => $numbers
        ]);
    }

    /**
     * Store a newly created number.
     */
    public function store(NumberRequest $request)
    {
        // Store the uploaded files
        $imagePath = $request->file('image')->store('images/numbers', 'public');
        $audioPath = $request->file('audio')->store('audio/numbers', 'public');

        // Create the number record
        $number = Number::create([
            'number' => $request->number,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Number created successfully',
            'data' => $number
        ], 201);
    }

    /**
     * Display the specified number.
     */
    public function show($id)
    {
        $number = Number::find($id);

        if (!$number) {
            return response()->json([
                'success' => false,
                'message' => 'Number not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $number
        ]);
    }

    /**
     * Update the specified number.
     */
    public function update(NumberRequest $request, $id)
    {
        $number = Number::find($id);

        if (!$number) {
            return response()->json([
                'success' => false,
                'message' => 'Number not found'
            ], 404);
        }

        // Update fields if provided
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($number->image_path);
            $number->image_path = $request->file('image')->store('images/numbers', 'public');
        }

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($number->audio_path);
            $number->audio_path = $request->file('audio')->store('audio/numbers', 'public');
        }

        if ($request->has('number')) {
            $number->number = $request->number;
        }

        $number->save();

        return response()->json([
            'success' => true,
            'message' => 'Number updated successfully',
            'data' => $number
        ]);
    }

    /**
     * Remove the specified number.
     */
    public function destroy($id)
    {
        $number = Number::find($id);

        if (!$number) {
            return response()->json([
                'success' => false,
                'message' => 'Number not found'
            ], 404);
        }

        // Delete files
        Storage::disk('public')->delete($number->image_path);
        Storage::disk('public')->delete($number->audio_path);

        $number->delete();

        return response()->json([
            'success' => true,
            'message' => 'Number deleted successfully'
        ]);
    }
}
