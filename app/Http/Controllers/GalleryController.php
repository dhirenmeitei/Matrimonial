<?php

namespace App\Http\Controllers;

use App\Models\PostModel;

class GalleryController extends Controller
{
    public function index()
    {
        $posts = PostModel::withCount(['likes', 'comments'])
            ->whereNotNull('photo')
            ->latest()
            ->get();

        return view('gallery.index', compact('posts'));
    }
}
