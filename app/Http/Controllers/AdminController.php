<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;

class AdminController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        return view('admin.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('json_file');
        $path = $file->store('temp');

        try {
            $this->importService->importJson(storage_path('app/' . $path));
            $message = 'Import completed successfully.';
        } catch (\Exception $e) {
            $message = 'Import failed: ' . $e->getMessage();
        }

        // Delete the temporary file
        \Storage::delete($path);

        $stats = [
            'users' => \App\Models\User::count(),
            'posts' => \App\Models\Post::count(),
            'tags' => \App\Models\Tag::count(),
        ];

        return redirect()->route('admin.index')->with('import_result', [
            'message' => $message,
            'stats' => $stats,
        ]);
    }
}