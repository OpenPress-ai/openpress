<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Import JSON Data</h3>
                    <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label for="json_file" class="block text-sm font-medium text-gray-700">Select JSON file:</label>
                            <input type="file" name="json_file" id="json_file" accept=".json" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Import Data
                        </button>
                    </form>

                    @if (session('import_result'))
                        <div class="mt-4 p-4 border rounded {{ session('import_result')['message'] === 'Import completed successfully.' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }}">
                            <p class="font-semibold">{{ session('import_result')['message'] }}</p>
                            @if (isset(session('import_result')['stats']))
                                <ul class="mt-2 list-disc list-inside">
                                    <li>Users imported: {{ session('import_result')['stats']['users'] }}</li>
                                    <li>Posts imported: {{ session('import_result')['stats']['posts'] }}</li>
                                    <li>Tags imported: {{ session('import_result')['stats']['tags'] }}</li>
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>