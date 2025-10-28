<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostReportRequest;
use App\Models\Post;
use App\Models\PostReport;
use Illuminate\Http\Request;

class PostReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(PostReport::filter()->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostReportRequest $request, Post $post)
    {
        
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id; // Assuming the user is authenticated

        // Create a new post report with the validated data
        $report = $post->reports()->create($validated);

        return response()->json(['message' => 'Post reported successfully', 'report' => $report], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
