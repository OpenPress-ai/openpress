<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($id)
    {
        $post = Post::with(['tags', 'author'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }
}