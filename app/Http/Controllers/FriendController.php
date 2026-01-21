<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 occupation code => name
        $occupations = DB::table('occupations')
            ->orderBy('name')
            ->pluck('name', 'code') // key = code, value = name
            ->toArray();

        $query = User::where('id', '!=', auth()->id());

        if ($request->occupation && $request->occupation !== 'all') {
            $query->where('occupation', $request->occupation); // match CODE
        }

        $users = $query->latest()->get();

        return view('friends.index', compact('users', 'occupations'));
    }
}
