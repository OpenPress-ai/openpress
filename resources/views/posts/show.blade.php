@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $post->title }}</h1>
    <p>By {{ $post->author->name }} | Published: {{ $post->published_at }}</p>
    
    @if($post->tags->isNotEmpty())
    <div class="tags">
        Tags:
        @foreach($post->tags as $tag)
            <span class="tag">{{ $tag->name }}</span>
        @endforeach
    </div>
    @endif
    
    <div class="content">
        {!! $post->mobiledoc !!}
    </div>
</div>
@endsection