<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::with('author', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('posts'));
    }
}