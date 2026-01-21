<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use App\Models\PostModel;
use App\Models\PostLikeModel;
use App\Models\Follow;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $posts = PostModel::with(['user', 'likes', 'comments.user'])
            ->latest()
            ->get();

        return view('timeline.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('posts', 'public');
        }

        PostModel::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'photo' => $photoPath
        ]);

        return back()->with('success', 'Post uploaded successfully!');
    }

    // public function like_old($id)
    // {
    //     $like = PostLikeModel::where('post_id', $id)
    //         ->where('user_id', auth()->id())
    //         ->first();

    //     if ($like) {
    //         $like->delete(); // unlike
    //     } else {
    //         PostLikeModel::create([
    //             'post_id' => $id,
    //             'user_id' => auth()->id()
    //         ]);
    //     }

    //     return back();
    // }
    public function like($id)
    {
        $like = PostLikeModel::where('post_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            PostLikeModel::create([
                'post_id' => $id,
                'user_id' => auth()->id()
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => PostLikeModel::where('post_id', $id)->count()
        ]);
    }


    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = PostComment::create([
            'post_id' => $id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        // Load user relationship for blade/js
        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_id' => $comment->user_id,
                'username' => $comment->user->username
            ]
        ]);
    }


    // public function deleteComment(PostComment $comment)
    // {
    //     // Allow only comment owner
    //     if ($comment->user_id !== auth()->id()) {
    //         abort(403);
    //     }

    //     $comment->delete();

    //     return back()->with('success', 'Comment deleted');
    // }
    public function deleteComment(PostComment $comment)
    {
        // Only comment owner can delete
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'comment_id' => $comment->id
        ]);
    }

}
