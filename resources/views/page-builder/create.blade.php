<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Page Builder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Create New Page</h3>
                    <div id="page-builder">
                        <h4 class="text-md font-semibold mb-2">Available Components</h4>
                        <ul>
                            <li>Text Block</li>
                            <li>Image</li>
                            <li>Button</li>
                        </ul>
                        <!-- Add more page builder content here -->
                    </div>
                    <!-- Add your page creation form here -->
                    <p>Page creation form will be implemented here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script src="{{ asset('js/page-builder.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page-builder.css') }}">
@endpush