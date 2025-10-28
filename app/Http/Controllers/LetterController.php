<?php

namespace App\Http\Controllers;

use App\Http\Requests\LetterRequest;
use App\Models\Letter;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    /**
     * Display a listing of the letters.
     */
    public function index()
    {
        $letters = Letter::all();

        return response()->json([
            'success' => true,
            'data' => $letters
        ]);
    }

    /**
     * Store a newly created letter.
     */
    public function store(LetterRequest $request)
    {
        // Store the uploaded files
        $imagePath = $request->file('image')->store('images/letters', 'public');
        $audioPath = $request->file('audio')->store('audio/letters', 'public');
        $wordAudioPath = $request->file('word_audio')->store('audio/letters', 'public');

        // Create the letter record
        $letter = Letter::create([
            'letter' => $request->letter,
            'word' => $request->word,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
            'word_audio' => $wordAudioPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Letter created successfully',
            'data' => $letter
        ], 201);
    }

    /**
     * Display the specified letter.
     */
    public function show($id)
    {
        $letter = Letter::find($id);

        if (!$letter) {
            return response()->json([
                'success' => false,
                'message' => 'Letter not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $letter
        ]);
    }

    /**
     * Update the specified letter.
     */
    public function update(LetterRequest $request, $id)
    {
        $letter = Letter::find($id);

        if (!$letter) {
            return response()->json([
                'success' => false,
                'message' => 'Letter not found'
            ], 404);
        }

        // Update fields if provided
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($letter->image_path);
            $letter->image_path = $request->file('image')->store('images/letters', 'public');
        }

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($letter->audio_path);
            $letter->audio_path = $request->file('audio')->store('audio/letters', 'public');
        }
        if ($request->hasFile('word_audio')) {
            Storage::disk('public')->delete($letter->audio_path);
            $letter->word_audio = $request->file('word_audio')->store('audio/letters', 'public');
        }

        if ($request->has('letter')) {
            $letter->letter = $request->letter;
        }
        if ($request->has('word')) {
            $letter->word = $request->word;
        }

        $letter->save();

        return response()->json([
            'success' => true,
            'message' => 'Letter updated successfully',
            'data' => $letter
        ]);
    }

    /**
     * Remove the specified letter.
     */
    public function destroy($id)
    {
        $letter = Letter::find($id);

        if (!$letter) {
            return response()->json([
                'success' => false,
                'message' => 'Letter not found'
            ], 404);
        }

        // Delete files
        Storage::disk('public')->delete($letter->image_path);
        Storage::disk('public')->delete($letter->audio_path);

        $letter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Letter deleted successfully'
        ]);
    }
}
