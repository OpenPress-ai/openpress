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

        try {
            $path = $request->file('json_file')->storeAs('temp', 'import.json');

            Log::info('File stored at: ' . $path);
            Log::info('Full storage path: ' . storage_path('app/' . $path));
            Log::info('File exists: ' . (Storage::exists($path) ? 'Yes' : 'No'));

            $fullPath = storage_path('app/' . $path);
            Log::info('File permissions: ' . substr(sprintf('%o', fileperms($fullPath)), -4));
            Log::info('File owner: ' . fileowner($fullPath));
            Log::info('PHP process owner: ' . posix_getpwuid(posix_geteuid())['name']);

            if (!Storage::exists($path)) {
                throw new \Exception('Import file not found: ' . $path);
            }

            $contents = Storage::get($path);
            Log::info('File contents length: ' . strlen($contents));

            $this->importService->importJson($contents);
            $message = 'Import completed successfully.';
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            $message = 'Import failed: ' . $e->getMessage();
        }

        // Delete the temporary file
        if (isset($path) && Storage::exists($path)) {
            Storage::delete($path);
            Log::info('Temporary file deleted: ' . $path);
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