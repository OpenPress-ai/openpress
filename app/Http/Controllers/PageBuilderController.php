<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageBuilderController extends Controller
{
    public function index()
    {
        return view('page-builder.index');
    }

    public function create()
    {
        return view('page-builder.create');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('page-builder.edit', compact('page'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:pages',
            'content' => 'required|json',
        ]);

        $page = Page::create($validatedData);

        return response()->json($page, 201);
    }

    public function getElements()
    {
        // This would typically come from a database or configuration file
        $elements = [
            ['type' => 'text', 'name' => 'Text Block'],
            ['type' => 'image', 'name' => 'Image'],
            ['type' => 'button', 'name' => 'Button'],
        ];

        return response()->json($elements);
    }
}