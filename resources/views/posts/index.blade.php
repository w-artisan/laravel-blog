@extends('layout')

@section('title')
    List of Blog Posts
@endsection

@section('content')
    @forelse ($posts as $post)
            <h3>
                <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
            </h3>
            <p>
                {{ $post->content }}
            </p>
            <div class="row">
                <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
                    Edit
                </a>
                <form action="{{ route('posts.destroy', ['post' => $post->id]) }}"
                    method="post"
                    class="">
                    @csrf
                    @method('DELETE')

                    <input type="submit" value='Delete!' class="btn btn-default"/>
                </form>
            </div>
            <p>
                Added {{ $post->created_at->diffForHumans() }}
            </p>
        @empty
        <p>No blog posts yet!</p>
    @endforelse

@endsection('content')
