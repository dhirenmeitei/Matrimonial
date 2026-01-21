<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle($id)
    {
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $id)
            ->first();

        if ($follow) {
            $follow->delete();
        } else {
            Follow::create([
                'follower_id' => auth()->id(),
                'following_id' => $id
            ]);
        }

        return back();
    }

    // follower
    public function followers(User $user)
    {
        // get followers list
        $followers = $user->followers()->latest()->get();

        return view('followers.index', compact('user', 'followers'));
    }
}
