<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return $post->comments()->with('user')->paginate(10);
    }

    public function store(Request $request, Post $post)
    {
        $fields = $request->validate([
            'body' => 'required|max:1000',
        ]);

        $comment = $post->comments()->create([
            'body' => $fields['body'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json($comment, 201);
    }

    public function show(Post $post, Comment $comment)
    {
        return response()->json($comment, 200);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        Gate::authorize('modify', $comment);

        $fields = $request->validate([
            'body' => 'required|max:1000',
        ]);

        $comment->update($fields);

        return response()->json($comment, 200);
    }

    public function destroy(Post $post, Comment $comment)
    {
        Gate::authorize('modify', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted'], 200);
    }
}
