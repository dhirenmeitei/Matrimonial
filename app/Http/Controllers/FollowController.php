<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Toggle follow / unfollow
    public function toggle($userId)
    {
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $userId)
            ->first();

        if ($follow) {
            $follow->delete(); // unfollow / cancel request
        } else {
            Follow::create([
                'follower_id' => auth()->id(),
                'following_id' => $userId,
                'status' => 'pending'
            ]);
        }

        return back();
    }

    // List followers of a user
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers; // collection of accepted followers

        return view('followers.list', compact('user', 'followers'));
    }

    // List follow requests sent to me
    public function requests()
    {
        $requests = auth()->user()
            ->followRequests()
            ->with('follower') // eager load follower user
            ->get();

        return view('followers.requests', compact('requests'));
    }

    // Accept a follow request
    public function accept($id)
    {
        $follow = Follow::where('id', $id)
            ->where('following_id', auth()->id())
            ->firstOrFail();

        $follow->update(['status' => 'accepted']);

        return back();
    }
}
