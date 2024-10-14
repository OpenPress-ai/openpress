<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Recent Posts') }}</h3>
                    @if($posts->count() > 0)
                        <ul class="space-y-4">
                            @foreach($posts as $post)
                                <li class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-lg font-medium">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('By') }} {{ $post->author->name }} | {{ $post->created_at->format('M d, Y') }}
                                    </p>
                                    @if($post->tags->isNotEmpty())
                                        <div class="mt-2">
                                            @foreach($post->tags as $tag)
                                                <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2 mb-2">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p>{{ __('No posts found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>