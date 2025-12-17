<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use App\Models\PostModel;
use App\Models\PostLikeModel;
use App\Models\User;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $posts = PostModel::with([
            'user',
            'likes',
            'comments.user'
        ])->latest()->get();

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

        return back();
    }

    public function like($id)
    {
        PostLikeModel::firstOrCreate([
            'post_id' => $id,
            'user_id' => auth()->id()
        ]);

        return back();
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        PostComment::create([
            'post_id' => $id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back();
    }
}
