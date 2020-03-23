@extends('layout')

@section('title')
    Show Blog Post
@endsection

@section('content')
    @if (session()->has('status'))
        <p style="color:green">
            {{ session()->get('status') }}
        </p>
    @endif
    <p>
        <a href="{{ route('posts.index') }}">Back to All Posts</a>
        <h3>
            <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
        </h3>
        <p>
            {{ $post->content }}
        </p>
        <p>Added {{ $post->created_at->diffForHumans() }}</p>
    </p>
    @if ($post->id === 1)
        Post one!
    @elseif ($post->id === 2)
        Post two!
    @else
        Someting else!
    @endif

@endsection('content')
