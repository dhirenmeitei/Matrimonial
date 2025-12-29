<?php

namespace App\Http\Controllers;

use App\Models\PostModel;

class GalleryController extends Controller
{
    public function index()
    {
        $posts = PostModel::withCount(['likes', 'comments'])
            ->whereNotNull('photo')
            ->where('user_id', auth()->id())
            ->orderBy('created_at','desc')
            ->latest()
            ->get();
        // return $posts;

        return view('gallery.index', compact('posts'));
    }
}
