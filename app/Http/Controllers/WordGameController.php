<?php

namespace App\Http\Controllers;

use App\Models\WordGame;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\WordRequest;

class WordGameController extends Controller
{
    /**
     * Display a listing of words.
     */
    public function index(): JsonResponse
    {
        return response()->json(WordGame::all());
    }
    public function getRandomWord()
    {
        // Retrieve a random word from the database
        $word = WordGame::inRandomOrder()->first();

        // Check if a word exists
        if (!$word) {
            return response()->json(['message' => 'No words found'], 404);
        }

        return response()->json(['word' => $word->word]);
    }
    /**
     * Store a newly created word.
     */
    public function store(Request $request): JsonResponse
    {
        $word = WordGame::create($request->validated());
        return response()->json($word, 201);
    }

    /**
     * Display the specified word.
     */
    public function show(WordGame $word): JsonResponse
    {
        return response()->json($word);
    }

    /**
     * Update the specified word.
     */
    public function update(Request $request, WordGame $word): JsonResponse
    {
        $word->update($request->validated());
        return response()->json($word);
    }

    /**
     * Remove the specified word.
     */
    public function destroy(WordGame $word): JsonResponse
    {
        $word->delete();
        return response()->json(['message' => 'WordGame deleted successfully']);
    }
}
