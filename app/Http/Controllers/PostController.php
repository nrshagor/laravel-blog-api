<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

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
        $fields =  $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $post = $request->user()->posts()->create($fields);
        // return ['post' => $post];
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Gate::authorize('modify', $post);
        // $fields =  $request->validate([
        //     'title' => 'required|max:255',
        //     'body' => 'required'
        // ]);
        // $post->update($fields);

        // return  $post;

        Gate::authorize('modify', $post);

        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post->update($fields);

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
