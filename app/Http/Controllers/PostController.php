<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(PostModel $post)
    {
        $post->load(['user', 'likes.user', 'comments.user']);

        return view('posts.show', compact('post'));
    }
    

    //
    public function destroy(PostModel $post)
    {
        // Allow only post owner
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete post image if exists
        if ($post->photo && \Storage::disk('public')->exists($post->photo)) {
            \Storage::disk('public')->delete($post->photo);
        }

        // Delete related likes & comments
        $post->likes()->delete();
        $post->comments()->delete();

        // Delete post
        $post->delete();

        return back()->with('success', 'Post deleted successfully');
    }
}
