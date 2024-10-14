@extends('layouts.post')

@section('title', $post->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    By {{ $post->author->name }} | Published: {{ $post->published_at->format('F j, Y') }}
                </p>
                
                @if($post->tags->isNotEmpty())
                <div class="mb-4">
                    @foreach($post->tags as $tag)
                        <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2 mb-2">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                @endif
                
                <div class="prose dark:prose-invert max-w-none">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection