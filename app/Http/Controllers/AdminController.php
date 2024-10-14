<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        Log::info('File stored at: ' . $path);
        Log::info('Full storage path: ' . storage_path('app/' . $path));
        Log::info('File exists: ' . (Storage::exists($path) ? 'Yes' : 'No'));

        $fullPath = storage_path('app/' . $path);
        Log::info('File permissions: ' . substr(sprintf('%o', fileperms($fullPath)), -4));
        Log::info('File owner: ' . fileowner($fullPath));
        Log::info('PHP process owner: ' . posix_getpwuid(posix_geteuid())['name']);

        try {
            Log::info('Attempting to read file: ' . $fullPath);
            $contents = file_get_contents($fullPath);
            Log::info('File contents length: ' . strlen($contents));

            $this->importService->importJson($fullPath);
            $message = 'Import completed successfully.';
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            $message = 'Import failed: ' . $e->getMessage();
        }

        // Delay file deletion
        sleep(1);

        // Delete the temporary file
        if (Storage::exists($path)) {
            Storage::delete($path);
            Log::info('Temporary file deleted: ' . $path);
        } else {
            Log::warning('Temporary file not found for deletion: ' . $path);
        }

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