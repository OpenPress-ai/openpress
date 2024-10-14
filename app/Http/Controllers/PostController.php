<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($id)
    {
        $post = Post::with(['tags', 'author'])->findOrFail($id);
        $post->content = $this->parseMobileDoc($post->mobiledoc);
        return view('posts.show', compact('post'));
    }

    private function parseMobileDoc($mobiledoc)
    {
        $content = json_decode($mobiledoc, true);
        $html = '';

        if (isset($content['cards'])) {
            foreach ($content['cards'] as $card) {
                if ($card[0] === 'html') {
                    $html .= $card[1]['html'];
                }
            }
        }

        // Remove extra newlines and trim
        $html = trim(preg_replace('/\n+/', "\n", $html));

        // Convert newlines to <br> tags
        $html = nl2br($html);

        return $html;
    }
}