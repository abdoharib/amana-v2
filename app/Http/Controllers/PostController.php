<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return the posts as a resource collection
        return PostResource::collection(Post::filter()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['is_pinned'] = $request->user()->role === 'admin' ? $request->is_pinned : false;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
            $media['image'] = asset('storage/' . $imagePath);
        }

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('posts/videos', 'public');
            $media['video'] = asset('storage/' . $videoPath);
        }
        // add media to validated data
        $validated['media'] = isset($media) ? $media : null;
        // Create a new post with the validated data
        $post = Post::create($validated);
        $post->categories()->sync($validated['categories'] ?? []);

        // Return the created post as a resource
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'post' => new PostResource($post),
            'comments' => $post->comments,
        ]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
       
        $this->authorize('update', $post);

        // Update the post with validated data
        $validated = $request->validated();

        $validated['published_at'] =
            isset($validated['is_published']) && $validated['is_published'] ?
            now() : null;

        $post->update($validated);

        return new PostResource($post);
    }

    public function toggleLike(Post $post)
    {
        $user = request()->user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->detach($user->id);
            $status = 'unliked';
        } else {
            $post->likes()->attach($user->id);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $post->likes()->count(),
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete the post
        $post->delete();

        // Return a success response
        return response()->json(['message' => 'Post deleted successfully']);
    }
  
}
