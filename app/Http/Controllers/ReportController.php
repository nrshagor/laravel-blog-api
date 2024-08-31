<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    // Fetch top 5 users with the most posts
    public function topUsers()
    {
        $topUsers = Cache::remember('top_users_with_most_posts', 60 * 60, function () {
            return User::withCount('posts')
                ->orderBy('posts_count', 'desc')
                ->take(5)
                ->get();
        });

        return response()->json($topUsers);
    }

    // Fetch top 5 posts with the most comments
    public function topPosts()
    {
        $topPosts = Cache::remember('top_posts_with_most_comments', 60 * 60, function () {
            return Post::withCount('comments')
                ->orderBy('comments_count', 'desc')
                ->take(5)
                ->with('user') // Eager load the user relationship to reduce queries
                ->get();
        });

        return response()->json($topPosts);
    }
}
