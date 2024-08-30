<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::with('user')->paginate(10);
        // return $posts;
        $posts = Post::with('user')->paginate(3);
        return PostResource::collection($posts)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048', // Validate image file
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $timestamp = now()->format('Ymd_His_u'); // Timestamp format: YYYYMMDD_HHMMSS_UUUUUU
            $filename = $timestamp . '_' . $file->getClientOriginalName(); // Append timestamp to the original file name
            $filepath = $file->storeAs('images', $filename, 'public'); // Store in public/images
            $fields['thumbnail'] = $filepath;
        }

        $post = $request->user()->posts()->create($fields);

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }



    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // return ['post' => $post];
        return new PostResource($post->load('user'));
    }


    public function update(Request $request, Post $post)
    {
        // Log the incoming request data
        Log::info('Update request data:', $request->all());

        // Authorize user to update the post
        Gate::authorize('modify', $post);

        // Validate the incoming request data
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'thumbnail' => 'nullable|string', // Expect a base64-encoded string
        ]);

        // Handle the thumbnail file if provided as a base64 string
        if ($request->filled('thumbnail')) {
            // Delete the old thumbnail if it exists
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            // Extract base64 data from the string
            $base64Image = $request->input('thumbnail');
            $imageParts = explode(';', $base64Image);
            $imageBase64 = explode(',', $imageParts[1])[1];
            $imageData = base64_decode($imageBase64);

            // Generate a unique filename
            $timestamp = now()->format('Ymd_His_u');
            $filename = $timestamp . '_thumbnail.png'; // Use appropriate file extension
            $filepath = 'images/' . $filename;

            // Store the image in the public/images directory
            Storage::disk('public')->put($filepath, $imageData);
            $fields['thumbnail'] = $filepath;
        }

        // Update the post with the new data
        $post->update($fields);

        // Log updated post data
        Log::info('Updated post:', $post->toArray());

        return (new PostResource($post))
            ->response()
            ->setStatusCode(200);
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Gate::authorize('modify', $post);
        // $post->delete();
        // return ['message' => 'The Post was deleted'];
        Gate::authorize('modify', $post);

        $post->delete();

        return response()->json([
            'message' => 'The Post was deleted'
        ], 204);
    }
}
