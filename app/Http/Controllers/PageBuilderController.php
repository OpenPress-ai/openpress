<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageBuilderController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('page-builder.index', compact('pages'));
    }

    public function create()
    {
        return view('page-builder.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:pages',
            'content' => 'required|json',
        ]);

        $page = Page::create($validatedData);

        return redirect()->route('page-builder.edit', $page)->with('success', 'Page created successfully');
    }

    public function edit(Page $page)
    {
        return view('page-builder.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:pages,slug,' . $page->id,
            'content' => 'required|json',
        ]);

        $page->update($validatedData);

        return redirect()->route('page-builder.edit', $page)->with('success', 'Page updated successfully');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('page-builder.index')->with('success', 'Page deleted successfully');
    }

    public function getElements()
    {
        $elements = [
            ['type' => 'text', 'name' => 'Text Block'],
            ['type' => 'image', 'name' => 'Image'],
            ['type' => 'button', 'name' => 'Button'],
        ];

        return response()->json($elements);
    }
}